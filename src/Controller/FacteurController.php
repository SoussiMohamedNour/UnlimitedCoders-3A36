<?php

namespace App\Controller;

use App\Entity\Facteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormfacteurType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
class FacteurController extends AbstractController
{
    #[Route('/facteur', name: 'app_facteur')]
    public function index(): Response
    {
        
        return $this->render('facteur/index.html.twig',  [
            'controller_name' => 'DefaultController',
        ]);
    }
    #[Route('/facteur', name: 'app_facteur')]
    public function sendfacteur(ManagerRegistry $doctrine, Request $req): Response
    {   
        $em =$doctrine->getManager();
        $Facteur = new Facteur();
        $form = $this->createForm(FormfacteurType::class, $Facteur);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($Facteur);
            $em->flush();
            return $this->redirectToRoute('app_ficheassurance');}
        return $this->render('facteur/index.html.twig', [
            'formfacteur' => $form->createView()
        ]);
    }
}
