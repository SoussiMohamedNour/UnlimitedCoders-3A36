<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/json')]
class JsonController extends AbstractController
{

    #[Route("/allCategories", name: "ff")]
    public function getAllCategorie(CategorieRepository $repo, NormalizerInterface $normalizer)
    {
        $categorie = $repo->findAll();
        $categoriesNormalises = $normalizer->normalize($categorie, 'json', ['groups' => 'categories']);
        $json = json_encode($categoriesNormalises);
        return new Response($json);
    }

    #[Route("/newCat", name: "ddd")]
    public function addCatgorie(Request $request, NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = new Categorie();
        $categorie->setNom($request->get('nom'));
        $categorie->setDescription($request->get('description'));
        $em->persist($categorie);
        $em->flush();
        $jsonContent = $normalizer->normalize($categorie, 'json', ['groups' => 'categories']);
        return new Response(json_encode($jsonContent));
    }

    #[Route("/categorie/{id}", name: "aza")]
    public function categorieId($id, NormalizerInterface $normalizer, CategorieRepository $repo)
    {
        $categorie = $repo->find($id);
        $categorieRepository = $normalizer->normalize($categorie, 'json', ['groups' => 'categories']);
        return new Response(json_encode($categorieRepository));
    }

    #[Route("/delete/{id}", name: "azaz")]
    public function delete(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);
        $em->remove($categorie);
        $em->flush();
        $jsonContent = $Normalizer->normalize($categorie, 'json', ['groups' => "categories"]);

        return new Response("Categorie deleted successfully" . json_encode($jsonContent));
    }

    #[Route("/update/{id}", name: "fezgzr")]
    public function update(Request $req, $id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);
        $categorie->setNom($req->get('nom'));
        $categorie->setDescription($req->get('description'));
        $em->flush();
        $jsonContent = $Normalizer->normalize($categorie, 'json', ['groups' => 'categories']);
        return new Response("Categorie updated successfully" . json_encode($jsonContent));
    }


}
