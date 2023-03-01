<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Form\CalendrierType;
use App\Repository\CalendrierRepository;
use App\Repository\RendezVousRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendrier')]
class CalendrierController extends AbstractController
{
    #[Route('/', name: 'app_calendrier_index', methods: ['GET'])]
    public function index(CalendrierRepository $calendrierRepository): Response
    {
        return $this->render('calendrier/index.html.twig', [
            'calendriers' => $calendrierRepository->findAll()
        ]);
    }

    #[Route('/new/{medecin}', name: 'app_calendrier_new', methods: ['GET', 'POST', 'HEAD'])]
    public function new(Request $request, CalendrierRepository $calendrierRepository, $medecin, UtilisateurRepository $utilisateurRepository): Response
    {
        $calendrier = new Calendrier();
        $utilisateur = $utilisateurRepository->find($medecin);

        $form = $this->createForm(CalendrierType::class, $calendrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendrier = $form->getData();
            $calendrier->setUtilisateur($utilisateur);
            $calendrierRepository->save($calendrier, true);

            return $this->redirectToRoute('app_calendrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendrier/new.html.twig', [
            'calendrier' => $calendrier,
            'form' => $form,
            'utilisateur' => $utilisateur
        ]);
    }

    #[Route('/{id}', name: 'app_calendrier_show', methods: ['GET'])]
    public function show(Calendrier $calendrier): Response
    {
        return $this->render('calendrier/show.html.twig', [
            'calendrier' => $calendrier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_calendrier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendrier $calendrier, CalendrierRepository $calendrierRepository): Response
    {
        $form = $this->createForm(CalendrierType::class, $calendrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendrierRepository->save($calendrier, true);

            return $this->redirectToRoute('app_calendrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendrier/edit.html.twig', [
            'calendrier' => $calendrier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendrier_delete', methods: ['POST'])]
    public function delete(Request $request, Calendrier $calendrier, CalendrierRepository $calendrierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $calendrier->getId(), $request->request->get('_token'))) {
            $calendrierRepository->remove($calendrier, true);
        }

        return $this->redirectToRoute('app_calendrier_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/planning/{medecin}', name: 'app_planning', methods: ['GET'])]
    public function planning(UtilisateurRepository $utilisateurRepository, RendezVousRepository $rendezVousRepository, $medecin): Response
    {
        $utilisateur = $utilisateurRepository->find($medecin);
        $rendezVouses = $rendezVousRepository->findBy(['utilisateur' => $utilisateur]);

        return $this->render('calendrier/planning.html.twig', [
            'utilisateur' => $utilisateur,
            'rendezVouses' => $rendezVouses,
        ]);
    }

    #[Route('/dispo/{medecin}', name: 'app_dispo', methods: ['GET', 'HEAD'])]
    public function disponibilite(CalendrierRepository $calendrierRepository, UtilisateurRepository $utilisateurRepository, $medecin): Response
    {

        return $this->render('calendrier/dispo.html.twig', [
            'calendriers' => $calendrierRepository->findBy(['utilisateur' => $medecin])
        ]);
    }
}

