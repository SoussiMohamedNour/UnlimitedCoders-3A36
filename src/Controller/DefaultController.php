<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use App\dto\Pie;

class DefaultController extends AbstractController
{
    #[Route('/backoffice', name: 'app_default', methods: ['GET', 'POST'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        $results = $produitRepository->chart_repository();


        $totalCount = array_reduce($results, function ($carry, $result) {
            return $carry + $result['count'];
        }, 0);


        $resultArray = [];

        foreach ($results as $result) {
            $percentage = round($result['count']);
            $obj = new Pie();
            $obj->value = $result['nom'];
            $obj->valeur = $percentage;
            $resultArray[] = $obj;
        }

        return $this->render('BackOffice/base.html.twig', [
            'controller_name' => 'DefaultController',
            'results'  =>  $resultArray,
        ]);
    }
}
