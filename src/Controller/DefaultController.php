<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/backoffice', name: 'app_default')]
    public function index1(): Response
    {
        return $this->render('BackOffice/base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);

    }
    #[Route('/Frontoffice', name: 'home')]
    public function index2(): Response
        {
            return $this->render('Frontoffice/base.html.twig', [
                'controller_name' => 'DefaultController',
            ]);
        }
   
    
}
