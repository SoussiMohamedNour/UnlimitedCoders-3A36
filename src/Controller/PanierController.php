<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Panier;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(SessionInterface $session , ProduitRepository $ProductRepository,CommandeRepository $commandeRepository)
    {
        $commande = new Commande();
        $pannier = $session->get('pannier', []);
        $pannierxithData = [];
        foreach ($pannier as $id => $quantity) {
            $pannierxithData[] = [
                'produit' => $ProductRepository->find($id),
                'quantity' => $quantity,
            ];
        }
        //get full cart  total
        $total =0;
        $qte =0;

        foreach($pannierxithData as $item) {
            $totalItem = $item['produit']->getPrix()*$item['quantity'];
            $total += $totalItem;
            $qte +=  $item['quantity'];
        }

        $commande->setQuantite($qte);
        $commande->setPrix($total);
        $commandeRepository->save($commande, true);

        return $this->render('panier/index.html.twig', [
            'items' => $pannierxithData,
            'total' =>$total,
            'qte' =>$qte,
        ]);
    }


    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, SessionInterface $session)
    {
    $pannier = $session->get('pannier', []);

    if (!empty($pannier[$id])) {
    $pannier[$id]++;
    } else {
    $pannier[$id] = 1;
    }
    $session->set('pannier', $pannier);

    return $this->redirectToRoute('cart');

    }

    #[Route('/cart/remove/', name: 'cart_remove_all')]
    public function removeAll(SessionInterface $session)
    {
        $pannier = $session->get('pannier', []);
        unset($pannier);
        $session->set('pannier', []);
        return $this->redirectToRoute('cart');
    }


    #[Route('/comm/{total}', name: 'comm_all')]
    public function commande (int $total){
        Stripe::setApiKey('sk_test_51KpExdF9iBgMr9KtelIclB86Kdt08yTWbAnfeVekpuYWqmchRZ7vwSO71OnqCIvdDzBV5FCaDLauitKJYIaDGmM000LpTGuwE4');
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'ttd',
                        'product_data' => [
                            'name' => 'Subsciption',
                        ],
                        'unit_amount'  =>$total * 100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),

        ]);
        return $this->redirect($session->url, 303);
    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('panier/cancel.html.twig', []);
    }

    #[Route('/success_url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('panier/success.html.twig', []);
    }
}
