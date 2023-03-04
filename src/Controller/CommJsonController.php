<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



#[Route("/jj")]
class CommJsonController extends AbstractController
{

        #[Route("/allCommandesz", name: "mmddd")]
    public function getAllCommande(CommandeRepository $repo, NormalizerInterface $normalizer)
    {
        $cmd = $repo->findAll();
        $cmdNormalises = $normalizer->normalize($cmd, 'json', ['groups' => 'commandes']);
        $json = json_encode($cmdNormalises);
        return new Response($json);
    }

    #[Route("/newC", name: "szzs")]
    public function addCmd(Request $request, NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $cmd = new Commande();
        $cmd->setPrix($request->get('prix'));
        $cmd->setQuantite($request->get('quantite'));
        $em->persist($cmd);
        $em->flush();
        $jsonContent = $normalizer->normalize($cmd, 'json', ['groups' => 'commandes']);
        return new Response(json_encode($jsonContent));
    }

    #[Route("/cmd/{id}", name: "qddq")]
    public function cmdId($id, NormalizerInterface $normalizer, CommandeRepository $repo)
    {
        $cmd = $repo->find($id);
        $cmdRepository = $normalizer->normalize($cmd, 'json', ['groups' => 'commandes']);
        return new Response(json_encode($cmdRepository));
    }

    #[Route("/deleteC/{id}", name: "assa")]
    public function deleteC(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $cmd = $em->getRepository(Commande::class)->find($id);
        $em->remove($cmd);
        $em->flush();
        $jsonContent = $Normalizer->normalize($cmd, 'json', ['groups' => "commandes"]);

        return new Response("Commande deleted successfully" . json_encode($jsonContent));
    }

    #[Route("/updateC/{id}", name: "ddaa")]
    public function updateC(Request $req, $id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $cmd = $em->getRepository(Commande::class)->find($id);
        $cmd->setPrix($req->get('prix'));
        $cmd->setQuantite($req->get('quantite'));


        $em->flush();

        $jsonContent = $Normalizer->normalize($cmd, 'json', ['groups' => 'commandes']);
        return new Response("Commande updated successfully" . json_encode($jsonContent));
    }

}
