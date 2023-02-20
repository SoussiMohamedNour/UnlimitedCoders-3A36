<?php

namespace App\Controller;

use App\Entity\FicheAssurance;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FicheDeAssuranceType;
use Symfony\Component\HttpFoundation\Request;


class FicheassuranceController extends AbstractController
{
    #[Route('/ficheassurance', name: 'app_ficheassurance')]
    public function index(ManagerRegistry $doctrine, Request $req): Response
    {   
        $em =$doctrine->getManager();
        $FicheAssurance = new FicheAssurance();
        $form = $this->createForm(FicheDeAssuranceType::class, $FicheAssurance);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($FicheAssurance);
            $em->flush();
            return $this->redirectToRoute('app_ficheassurance');}
            return $this->render('ficheassurance/index.html.twig', [
                'form' => $form->createView()
            ]);
    }
    
   
}
