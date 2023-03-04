<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\CommandeRepository;
use Composer\Autoload\ClassLoader;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice')]
class CommandeController extends AbstractController
{

    #[Route('/commande', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/commande/{id}', name: 'app_commande_delete', methods: ['GET'])]
    public function delete(Request $request, Commande $commande, FlashyNotifier $flashyNotifier): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($commande);
        $em->flush();
        $flashyNotifier->error('Commande supprimé');

        return $this->redirectToRoute('app_commande_index');
    }
}
