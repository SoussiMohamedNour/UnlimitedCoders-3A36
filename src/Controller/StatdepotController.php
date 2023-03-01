<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Depot;
use App\Form\DepotType1;
use App\Form\DepotType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Repository\DepotRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatdepotController extends AbstractController
{
    #[Route('/statdepot', name: 'app_statd_epot')]
    public function index(): Response
    {
        return $this->render('statdepot/index.html.twig', [
            'controller_name' => 'StatdepotController',
        ]);
    }
    #[Route('/recherche', name:'app_recherche', methods:['GET', 'POST'])]
public function rechercheByNiveauAction(Request $request, DepotRepository $depotRepository): Response
    {
        $id=1;
          

        $form = $this->createFormBuilder(null)
            ->add('recherche', TextType::class)
            ->add('rechercher', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $depots = $depotRepository->searchM($form->get('recherche')->getData(),$id);

            return $this->render('depot/showpatient.html.twig', [
                'depots' => $depots,
                'form' => $form->createView(),
            ]);
        }

        $depots = $depotRepository->findBy(['patient'=>$id]);
        return $this->render('depot/showpatient.html.twig', [
            'depots' => $depots,
            'form' => $form->createView(),
        ]);
}
}
