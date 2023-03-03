<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiquesController extends AbstractController
{
    // #[Route('/statistiques', name: 'app_statistiques')]
    // public function index(): Response
    // {
    //     return $this->render('statistiques/index.html.twig', [
    //         'controller_name' => 'StatistiquesController',
    //     ]);
    // }




    #[Route('/user/stat',name:'app_utilisateur_statistique')]
    public function statistique(UtilisateurRepository $utilisateurRepository)
    {
        $total_utilisateur = $utilisateurRepository->total_utilisateur();
        $total_utilisateur_banned = $utilisateurRepository->total_utilisateur_banned();
        $total_utilisateur_unbanned = $utilisateurRepository->total_utilisateur_unbanned ();

        return $this->renderForm('/statistiques/statistiques.html.twig',['total_utilisateur'=>$total_utilisateur,'total_utilisateur_unbanned'=>$total_utilisateur_unbanned,'total_utilisateur_banned'=> $total_utilisateur_unbanned]);


    }
}

