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

    #[Route('/backofficemed', name: 'app_medecin')]
    public function home(): Response
    {
        return $this->render('BackOffice/medecin.html.twig');
    }

    #[Route('/backofficeadmin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('BackOffice/admin.html.twig');
    }

    #[Route('/backofficeph', name: 'app_pharmacien')]
    public function index1(): Response
    {
        return $this->render('BackOffice/pharmacien.html.twig', [
            'controller_name' => 'DefaultController',
        ]);

    }


    #[Route('/frontoffice', name: 'app_home')]
    public function index2(): Response
    {
        return $this->render('Frontoffice/base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);

    }




    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        return $this->redirectToRoute('login/index.html.twig');
    }
}

    

