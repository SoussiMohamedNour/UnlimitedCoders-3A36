<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\SearchType;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home/page')]
class ProduitFrontController extends AbstractController
{

    #[Route('/produit', name: 'app')]
    public function indexP(ProduitRepository $produitRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $produits = $paginator->paginate($this->getDoctrine()->getRepository(Produit::class)->findAll(),
            $request->query->getInt('page',1),
            3
        );
        return $this->render("produit/produitFront.html.twig",array(
            'produits' => $produits));

    }


}
