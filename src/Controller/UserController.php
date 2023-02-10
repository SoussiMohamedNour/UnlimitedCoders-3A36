<?php

namespace App\Controller;
//test
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/frontoffice', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('Frontoffice/base.html.twig');
    }

    
}
