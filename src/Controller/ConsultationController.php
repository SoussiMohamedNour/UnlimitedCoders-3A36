<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Form\SortConsultationType;
use App\Repository\ConsultationRepository;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

use Knp\Component\Pager\PaginatorInterface;

#[Route('/backoffice')]
class ConsultationController extends AbstractController
{
    #[Route('/consultation', name: 'app_consultation_index', methods: ['GET','POST'])]
    public function index(ConsultationRepository $consultationRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SortConsultationType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $critere = $form->get('sort')->getData();
            $ordre = $form->get('ordre')->getData();
            $data = $consultationRepository->trier($critere,$ordre);
            $consultations = $paginator->paginate($data,$request->query->getInt('page',1),5);
        return $this->renderForm('/BackOffice/consultation/index.html.twig',['consultations'=> $consultations,'form'=>$form]);
            

        }
        $data = $consultationRepository->findAll();
        $consultations = $paginator->paginate($data,$request->query->getInt('page',1),5);
        return $this->renderForm('BackOffice/consultation/index.html.twig', [
            'consultations' => $consultations,
            'form'=>$form
        ]);

    }

    #[Route('/consultation/trier',name:'app_consultation_trier')]
    public function trier(Request $request,ConsultationRepository $consultationRepository):Response
    {
        $form = $this->createForm(SortConsultationType::class);
        $form->handleRequest($request);
        $consultations = $consultationRepository->findAll();

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $results = $consultationRepository->getConsultationByMontant();

        return $this->renderForm('/BackOffice/consultation/index.html.twig',[
            'consultations'=> $results,
            'form'=>$form
        ]);

        }

        return $this->renderForm('/BackOffice/consultation/new.html.twig',[
            'consultations'=> $consultations,
            'form'=>$form
        ]);


    }

    #[Route('/consultation/ajouter', name: 'app_consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConsultationRepository $consultationRepository): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultationRepository->save($consultation, true);

            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[Route('/consultation/show/{reference}', name: 'app_consultation_show', methods: ['GET'])]
    public function show(Consultation $consultation): Response
    {
        return $this->render('BackOffice/consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    #[Route('/consultation/modifier/{reference}', name: 'app_consultation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultationRepository->save($consultation, true);

            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[Route('/consultation/supprimer/{reference}', name: 'app_consultation_delete', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getReference(), $request->request->get('_token'))) {
            $consultationRepository->remove($consultation, true);
        }

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/consultation/pdf',name:'app_consultation_pdf')]
    public function genereatePDF(ConsultationRepository $repo):void

    {
        $consultation = $repo->findAll();
        $temps = date("h:i:sa");
        $pdf_options = new Options();
        $pdf_options->setDefaultFont('defaultFont','Arial');
        $dompdf = new Dompdf($pdf_options);
        $html = $this->renderForm('/BackOffice/consultation/pdf.html.twig',['consultation'=>$consultation,'date'=>$temps]);
        $dompdf->load_html($html);
        $dompdf->setPaper('A4','portrait');
        $dompdf->render();
        $dompdf->stream("Consultation.pdf",['attachement'=>false]);
    }
    // Workshop JSON
    #[Route('/consultation/index_json',name:'app_consultation_index_json')]
    public function indexJson(ConsultationRepository $repo,SerializerInterface $serializer)
    {
        $consultations = $repo->findAll();
        $json = $serializer->serialize($consultations,'json',['groups'=>'consultations']);
        return new Response($json);
    }
    #[Route('/consultation/recupererjson/{reference}',name:'app_consultation_recuperer_json')]
    public function recupererJson($reference,NormalizerInterface $normalizerInterface,ConsultationRepository $repo)
    {
        $consultation = $repo->find($reference);
        $consultationNormalizer = $normalizerInterface->normalize($consultation,'json',['groups'=>'consultations']);
        return new Response(json_encode($consultationNormalizer));
    }
    #[Route('/consultation/ajouter_json',name:'app_consultation_ajouter_json')]
    public function ajouterJson(Request $req,NormalizerInterface $normalizerInterface)
    {
        $format = "d-m-y";
        $em = $this->getDoctrine()->getManager();
        $consultation = new Consultation();
        $consultation->setMatriculemedecin($req->get('matricule'));
        $consultation->setIdpatient($req->get('id'));
        $consultation->setMontant($req->get('montant'));
        $date = \DateTimeImmutable::createFromFormat($format,$req->get('date'));
        $consultation->setDateconsultation($date);
        $em->persist($consultation);
        $em->flush();
        $jsonContent  = $normalizerInterface->normalize($consultation,'json',['groups'=>'consultations']);
        return new Response(json_encode($jsonContent));
    }
    #[Route('/consultation/modifierjson/{reference}',name:"app_consultation_modifier_json")]
    public function modifierJson(Request $request,$reference,NormalizerInterface $normalizerInterface)
    {
        $em =  $this->getDoctrine()->getManager();
        $consultation = $em->getRepository(Consultation::class)->find($reference);
        $consultation->setIdpatient($request->get('id'));
        $consultation->setMontant($request->get('montant'));
        $consultation->setMatriculemedecin($request->get('matricule'));
        $consultation->setDateconsultation($consultation->getDateconsultation());

        $em->flush();

        $jsonContent = $normalizerInterface->normalize($consultation,'json',['groups'=>'consultation']);
        return new Response("Concultation Modifiée".json_encode($jsonContent));

    }
    #[Route('/consultation/supprimerjson/{reference}',name:'app_consultaiton_suuprimer_json')]
    public function supprimerJson(Request $req,$reference,NormalizerInterface $normalizerInterface)
    {
        $em = $this->getDoctrine()->getManager();
        $consultation = $em->getRepository(Consultation::class)->find($reference);
        $em->remove($consultation);
        $em->flush();
        $jsonContent = $normalizerInterface->normalize($consultation,'json',['groups','consultations']);
        return new Response("Consultation Suppprimée".json_encode($jsonContent));
    }
    
}
