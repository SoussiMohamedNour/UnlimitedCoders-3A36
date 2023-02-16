<?php

namespace App\Controller;

use App\Entity\Medicament;
use App\Form\MedicamentType;
use App\Repository\MedicamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/backoffice')]
class MedicamentController extends AbstractController
{
    #[Route('/medicament', name: 'app_medicament_index', methods: ['GET'])]
    public function index(MedicamentRepository $medicamentRepository): Response
    {
        return $this->render('BackOffice/medicament/index.html.twig', [
            'medicaments' => $medicamentRepository->findAll(),
        ]);
    }

    #[Route('/medicament/ajouter', name: 'app_medicament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MedicamentRepository $medicamentRepository): Response
    {
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medicamentRepository->save($medicament, true);

            return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/medicament/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
        ]);
    }

    #[Route('/medicament/show/{id}', name: 'app_medicament_show', methods: ['GET'])]
    public function show(Medicament $medicament): Response
    {
        return $this->render('BackOffice/medicament/show.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    #[Route('/medicament/modifier/{id}', name: 'app_medicament_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Medicament $medicament, MedicamentRepository $medicamentRepository): Response
    {
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medicamentRepository->save($medicament, true);

            return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/medicament/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
        ]);
    }

    #[Route('/medicament/supprimer/{id}', name: 'app_medicament_delete', methods: ['POST'])]
    public function delete(Request $request, Medicament $medicament, MedicamentRepository $medicamentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medicament->getId(), $request->request->get('_token'))) {
            $medicamentRepository->remove($medicament, true);
        }

        return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/medicament/pdf',name:'app_medicament_pdf')]
    public function generatePDF(MedicamentRepository $repo):void 
    {
        $medicament = $repo->findAll();
        $temps = date("h:i:sa");
        $pdf_options = new Options();
        $pdf_options->setDefaultFont('defaultFont','Arial');
        $dompdf = new Dompdf($pdf_options);
        $html = $this->renderForm('/BackOffice/medicament/pdf.html.twig',['medicament'=>$medicament,'date'=>$temps]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','portrait');
        $dompdf->render();
        $dompdf->stream("Medicament.pdf",['attachement'=>false]);
    }

    // Workshop JSON
    #[Route('/medicament/index_json',name:'app_medicament_index_json')]
    public function indexJson(MedicamentRepository $repo,SerializerInterface $serializerInterface)
    {
        $medicaments = $repo->findAll();
        $json = $serializerInterface->serialize($medicaments,'json',['groups'=>'medicaments']);
        return new Response($json);
    }
    #[Route('/medicament/recuperer/{id}',name:'app_medicament_recuperer_json')]
    public function recupererJson($id,NormalizerInterface $normalizerInterface,MedicamentRepository $repo)
    {
        $medicament = $repo->find($id);
        $medicamentnormalizer = $normalizerInterface->normalize($medicament,'json',['groups'=>'medicaments']);
        return new Response(json_encode($medicamentnormalizer));
    }
    #[Route('/medicament/ajouter_json',name:'app_medicament_ajouter_json')]
    public function ajouterJson(Request $request,NormalizerInterface $normalizerInterface)
    {
        $em = $this->getDoctrine()->getManager();
        $medicament = new Medicament();
        $medicament->setNom($request->get('nom'));
        $medicament->setDosage($request->get('dosage'));
        $medicament->setPrix($request->get('prix'));
        $medicament->setDescription($request->get('description'));

        $em->persist($medicament);
        $em->flush();
        
        $jsonContent = $normalizerInterface->normalize($medicament,'json',['groups'=>'medicaments']);
        return new Response(json_encode($jsonContent));
    }
    #[Route('/medicament/modifier/{id}',name:'app_medicament_modifier_json')]
    public function modifierJson(Request $request,$id,NormalizerInterface $normalizerInterface)
    {
        $em = $this->getDoctrine()->getManager();
        $medicament = $em->getRepository(Medicament::class)->find($id);
        $medicament->setNom($request->get('nom'));
        $medicament->setDosage($request->get('dosage'));
        $medicament->setPrix($request->get('prix'));
        $medicament->setDescription($request->get('description'));

        $em->flush();
        $jsonContent = $normalizerInterface->normalize($medicament,'json',['groups'=>'medicaments']);
        return new Response("Médicament Modifié avec succès".json_encode($jsonContent));
    }
    #[Route('/medicament/supprimer/{id}',name:'app_medicament_supprimer_json')]
    public function supprimerJson(Request $request,$id,NormalizerInterface $normalizerInterface)
    {
        $em = $this->getDoctrine()->getManager();
        $medicament = $em->getRepository(Medicament::class)->find($id);
        $em->remove($medicament);
        $em->flush();
        $jsonContent = $normalizerInterface->normalize($medicament,'json',['groups'=>'medicaments']);
        return new Response("Médicament supprimé avec cussès".json_encode($jsonContent));
    }
}
