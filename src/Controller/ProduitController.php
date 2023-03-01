<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\SearchType;
use App\Repository\ProduitRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/backoffice')]
class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit_index')]
    public function index(ProduitRepository $produitRepository,Request $request): Response
    {
        $formsearchI = $this->createForm(SearchType::class);
        $formsearchI->handleRequest($request);
        if ($formsearchI->isSubmitted()) {
            $nom = $formsearchI->getData();
            $TSearch = $produitRepository->search($nom['nom']);

            return $this->render("produit/index.html.twig",
                array("produits" => $TSearch,"formsearch" => $formsearchI->createView())) ;
        }
        return $this->render("produit/index.html.twig",array(
            "formsearch" => $formsearchI->createView(),
            'produits' => $produitRepository->findAll()));

    }

    #[Route('/produit/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            foreach($image as $ima){
                $fichier = md5(uniqid()) . '.' . $ima->guessExtension();
                $ima->move(
                    $this->getParameter('img_directory'),
                    $fichier
                );
            }
            $produit->setImage($fichier);
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/produit/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository,FlashyNotifier $flashyNotifier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
            $flashyNotifier->error('Produit supprimé');
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route("/allProduits", name: "list", methods: ['GET'])]
    public function getAllProduit(ProduitRepository $repo, NormalizerInterface $normalizer)
    {
        $p = $repo->findAll();
        $prodNormalises = $normalizer->normalize($p, 'json', ['groups' => 'produits']);
        $json = json_encode($prodNormalises);
        return new Response($json);
    }




    #[Route("/newP", name: "add")]
    public function addProduit(Request $request, NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $p = new Produit();
        $p->setNom($request->get('nom'));
        $p->setPrix($request->get('prix'));
        $p->setImage($request->get('image'));
        $p->setMatriculeAsseu($request->get('matricule_asseu'));
        $em->persist($p);
        $em->flush();
        $jsonContent = $normalizer->normalize($p, 'json', ['groups' => 'produits']);
        return new Response(json_encode($jsonContent));
    }


    #[Route("/produitJ/{id}", name: "find")]
    public function produitId($id, NormalizerInterface $normalizer, ProduitRepository $repo)
    {
        $p = $repo->find($id);
        $prodRepository = $normalizer->normalize($p, 'json', ['groups' => 'produits']);
        return new Response(json_encode($prodRepository));
    }


    #[Route("/deleteP/{id}", name: "delete")]
    public function deleteP(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Produit::class)->find($id);
        $em->remove($p);
        $em->flush();
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => "produits"]);

        return new Response("Produit deleted successfully" . json_encode($jsonContent));
    }


    #[Route("/updateP/{id}", name: "update")]
    public function updateP(Request $request, $id, NormalizerInterface $Normalizer)

    {
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Produit::class)->find($id);
        $p->setNom($request->get('nom'));
        $p->setPrix($request->get('prix'));
        $p->setImage($request->get('image'));
        $p->setMatriculeAsseu($request->get('matricule_asseu'));
        $em->flush();

        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'produits']);
        return new Response("Produit updated successfully" . json_encode($jsonContent));
    }

}
