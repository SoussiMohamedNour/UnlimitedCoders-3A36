<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Form\DepotType1;
use App\Form\DepotType;
use App\Repository\DepotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Model\Chart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/backoffice')]
class DepotController extends AbstractController
{
    #[Route('/depot', name: 'app_depot_index', methods: ['GET'])]
    function index(Request $request, DepotRepository $depotRepository,  PaginatorInterface $paginator): Response
    {
        $data = $depotRepository->findAll();

        $depots = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), // Current page number. If none is specified, default to 1.
            1 // Number of items to display per page
        );
    
        return $this->render('depot/index.html.twig', [
            'depots' => $depots,
        ]);
        $approuved = $depotRepository->countApprovedDepot();
        $refused = $depotRepository->countRefusDepot();
        return $this->render('depot/index.html.twig', [

            'depots' => $depotRepository->findAll(),
        ]);
    }
    #[Route('/stats', name: 'stats')]
    public function statistique(DepotRepository $depotRepo): Response
    {
        // on va chercher toutes les dossier 
        $depots = $depotRepo->findAll();
        $approuved = $depotRepo->countApprovedDepot();
        $refused = $depotRepo->countRefusDepot();
        $enattente = $depotRepo->countEnAttentDepot();
        //get number from array approuved
        $approuved1 = $approuved[0][1];
        $refused1 = $refused[0][1];
        $enattente1 = $enattente[0][1];
        $total1 = $approuved + $refused + $enattente;

        return $this->render('depot/stats.html.twig', [
            'depCount1' => $approuved1,
            'depCount2' => $refused1,
            'depCount3' => $enattente1,
        ]);
    }




    #[Route('/depot/new', name: 'app_depot_new', methods: ['GET', 'POST'])]
    function new(Request $request, DepotRepository $depotRepository): Response
    {
        $depot = new Depot();
        $form = $this->createForm(DepotType::class, $depot);
        $dateSystem = new \DateTime();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $idPatient = $form->get('patient')->getData()->getId();
            $dep = $depotRepository->findBy(['patient' => $idPatient]);
            $fiche = $depotRepository->getAllByFiche($form->get('fiche')->getData()->getId());
            $date = $fiche[0]->getDateFiche();
            $dateFiche = $date->format('Y-m-d');
            $dif = $dateSystem->diff($date);
            $days = $dif->days;
            if ($dep) {
                $assurance = $dep[0]->getAssurance();
                $oldPlafond = $dep[0]->getTotalDepense();
                $nomAssurance = $assurance->getNomAssurance();
                if ($form->get('assurance')->getData() != $assurance) {
                    $this->addFlash('danger', 'Le patient a déjà un depot pour l assurance ' . $nomAssurance . ' veuillez le selectionner');
                } else if ($days > 60) {

                    $this->addFlash('danger', 'La fiche est expirée');
                } else if ($form->get('regime')->getData() == "apci") {
                    $depot->setEtatDossier("en_attente");
                    $depotRepository->save($depot, true);
                } else if ($form->get('regime')->getData() == "maladie_ordinaire") {
                    $plafond = $assurance->getPlafond();

                    if (($form->get('totalDepense')->getData() > $plafond)) {
                        $this->addFlash('danger', 'Le plafond de l assurance ' . $nomAssurance . ' est dépassé');
                    } else if ($oldPlafond + $form->get('totalDepense')->getData() > (float) $plafond) {
                        $this->addFlash('danger', 'total depense depasse le plafond de l assurance ' . $nomAssurance);
                    } else {
                        $depot->setEtatDossier("en_attente");

                        $depotRepository->save($depot, true);
                    }
                }
            } else {

                $oldPlafond = 0;
                $assurance = $form->get('assurance')->getData();

                if ($form->get('regime')->getData() == "apci") {
                    $depot->setEtatDossier("en_attente");
                    $depotRepository->save($depot, true);
                } else if ($form->get('regime')->getData() == "maladie_ordinaire") {
                    $plafond = $assurance->getPlafond();
                    $nomAssurance = $assurance->getNomAssurance();
                    if (($form->get('totalDepense')->getData() > $plafond)) {
                        $this->addFlash('danger', 'Le plafond de l assurance ' . $nomAssurance . ' est dépassé');
                    } else if ($oldPlafond + $form->get('totalDepense')->getData() > (float) $plafond) {
                        $this->addFlash('danger', 'Le plafond de l assurance ' . $nomAssurance . ' est dépassé');
                    } else {
                        $depot->setEtatDossier("en_attente");
                        $depotRepository->save($depot, true);
                    }
                } elseif ($days > 60) {

                    $this->addFlash('danger', 'La fiche est expirée');
                }
            }
        }



        return $this->renderForm('depot/new.html.twig', [
            'depot' => $depot,
            'form' => $form,
        ]);
    }

    #[Route('/depot/{id}', name: 'app_depot_show', methods: ['GET'])]
    function show(Depot $depot): Response
    {
        return $this->render('depot/show.html.twig', [
            'depot' => $depot,
        ]);
    }

    #[Route('/depot/{id}/edit', name: 'app_depot_edit', methods: ['GET', 'POST'])]
    function edit(Request $request, Depot $depot, DepotRepository $depotRepository): Response
    {
        $form = $this->createForm(DepotType1::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $depotRepository->save($depot, true);

            return $this->redirectToRoute('app_depot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('depot/edit.html.twig', [
            'depot' => $depot,
            'form' => $form,
        ]);
    }


    #[Route('/depot/{id}', name: 'app_depot_delete', methods: ['POST'])]
    function delete(Request $request, Depot $depot): Response
    {
        if ($this->isCsrfTokenValid('delete' . $depot->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($depot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_depot_index', [], Response::HTTP_SEE_OTHER);
    }
}
