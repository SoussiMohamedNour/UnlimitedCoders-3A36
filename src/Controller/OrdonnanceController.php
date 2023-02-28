<?php

namespace App\Controller;

use App\Entity\Ordonnance;
use App\Form\MailerType;
use App\Form\OrdonnanceType;
use App\Form\SortOrdonnanceType;
use App\Repository\ConsultationRepository;
use App\Repository\OrdonnanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Iterator\DateRangeFilterIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/backoffice')]
class OrdonnanceController extends AbstractController
{
    #[Route('/ordonnance', name: 'app_ordonnance_index', methods: ['GET','POST'])]
    public function index(OrdonnanceRepository $ordonnanceRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SortOrdonnanceType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $critere = $form->get('sort')->getData();
            $ordere = $form->get('ordre')->getData();
            $data = $ordonnanceRepository->trier($critere,$ordere);
            $ordonnances = $paginator->paginate($data,$request->query->getInt('page',1),5);
            return $this->renderForm('/BackOffice/ordonnance/index.html.twig',['ordonnances'=>$ordonnances,'form'=>$form]);
        }
        $data = $ordonnanceRepository->findAll();
        $ordonnances = $paginator->paginate($data,$request->query->getInt('page',1),5);
        return $this->renderForm('/BackOffice/ordonnance/index.html.twig', [
            'ordonnances' => $ordonnances,'form'=>$form
        ]);
    }

    #[Route('/ordonnance/ajouter', name: 'app_ordonnance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OrdonnanceRepository $ordonnanceRepository,MailerInterface $mailer,ConsultationRepository $consultationRepository): Response
    {
        $ordonnance = new Ordonnance();
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ordonnanceRepository->save($ordonnance, true);
            $this->notifierValidite($consultationRepository,$ordonnanceRepository,$mailer,$ordonnance);

            return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('BackOffice/ordonnance/new.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
        ]);
    }

    #[Route('/ordonnance/show/{reference}', name: 'app_ordonnance_show', methods: ['GET'])]
    public function show(Ordonnance $ordonnance): Response
    {
        return $this->render('BackOffice/ordonnance/show.html.twig', [
            'ordonnance' => $ordonnance,
        ]);
    }

    #[Route('/ordonnance/modifier/{reference}', name: 'app_ordonnance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ordonnance $ordonnance, OrdonnanceRepository $ordonnanceRepository): Response
    {
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ordonnanceRepository->save($ordonnance, true);

            return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/ordonnance/edit.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
        ]);
    }

    #[Route('/ordonnance/supprimer/{reference}', name: 'app_ordonnance_delete', methods: ['POST'])]
    public function delete(Request $request, Ordonnance $ordonnance, OrdonnanceRepository $ordonnanceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ordonnance->getReference(), $request->request->get('_token'))) {
            $ordonnanceRepository->remove($ordonnance, true);
        }

        return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/ordonnance/pdf',name:'app_ordonnance_pdf')]
    public function generatePDF(OrdonnanceRepository $repo):void 
    {
        $ordonnance = $repo->findAll();
        $temps = date("h:i:sa");
        $pdf_options = new Options();
        $pdf_options->setDefaultFont('defaultFont','Arial');
        $dompdf = new Dompdf($pdf_options);
        $html = $this->renderForm('/BackOffice/ordonnance/pdf.html.twig',['ordonnance'=>$ordonnance,'date'=>$temps]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','portrait');
        $dompdf->render();
        $dompdf->stream("Ordonnance.pdf",['attachement'=>false]);
    }
    #[Route('/ordonnance/mail',name:'app_ordonnance_mail')]
    public function sendMail(OrdonnanceRepository $repo,MailerInterface $mailer,Request $request)
    {
        $form = $this->createForm(MailerType::class);
        $form->handleRequest($request);
        $email = (new Email());
        if($form->isSubmitted() && $form->isValid())
        {
            $from = $form->get('From')->getData();
            $to = $form->get('to')->getData();
            $subject = $form->get('subject')->getData();
            $text = $form->get('text')->getData();
            $file = $form->get('file')->getData();
            $email->from($from)
            ->to($to)
            ->subject($subject)
            ->text($text);
        }
        $mailer->send($email);
        return $this->renderForm('/BackOffice/ordonnance/email.html.twig',['form'=>$form]);
    }

    #[Route('/ordonnance/statistique',name:'app_ordonnance_statistique')]
    public function statistique(OrdonnanceRepository $ordonnanceRepository,ConsultationRepository $consultationRepository,Request $request)
    {
        $total_consultations = $consultationRepository->total_montant();
        $total_ordonnances = $ordonnanceRepository->total_ordonnances();
        $nombre_total_consultation = $consultationRepository->total_consultations();

        return $this->renderForm('/BackOffice/ordonnance/statistique.html.twig',['total_consultation'=>$total_consultations,'total_ordonnance'=>$total_ordonnances,'nombre_total_consultation'=>$nombre_total_consultation]);


    }

    // Commande Sync Msg  php bin/console messenger:consume async -vv
    #[Route('/ordonnance/notifier',name:'app_ordonnance_notifier')]
    public function notifierValidite(ConsultationRepository $consultationRepository, OrdonnanceRepository $repo,MailerInterface $mailer,Ordonnance $ordonnance)
    {
        $reference = $ordonnance->getReference();
        $validite = $ordonnance->getValidite();
        $id_consultation = $ordonnance->getConsultation()->getReference();
        $date_consultation = $consultationRepository->find($id_consultation)->getDateconsultation();
        $matricule_medecin = $consultationRepository->find($id_consultation)->getMatriculemedecin();
        $temps = date("h:i:sa");
        $date_today = date('Y-m-d');
        $date = new \DateTime($date_today);
        $date->add(new \DateInterval('P' . $validite . 'D'));
        $nouvelle_date = $date->format('Y-m-d');
        $ordonnance_trouvee = $repo->find($reference);
        
        $email = (new TemplatedEmail());
        $email->from('healthified.consultation.module@gmail.com')
        ->to('mohamednour.soussi@esprit.tn')
        ->subject('Rappel Consultation'. ' ' . $nouvelle_date)
        ->text('Service Mailing')
        ->htmlTemplate('/BackOffice/ordonnance/notifier.html.twig')
        ->context(['ordonnance'=>$ordonnance,'date'=>$date_today,'temps'=>$temps,'idConsultation'=>$id_consultation,'dateConsultation'=>$date_consultation->format('Y-m-d'),'matricule'=>$matricule_medecin,'nouvelleDate'=>$nouvelle_date
    ]);
        $mailer->send($email);

        

    }
    
    // Workshop JSON
    #[Route('/ordonnance/index_json',name:'app_ordonnance_index_json')]
    public function afficherJson(OrdonnanceRepository $repo,SerializerInterface $serializer)
    {
        $ordonnances = $repo->findAll();
        $json = $serializer->serialize($ordonnances,'json',['groups'=>'ordonnances']);
        return new Response($json);
    }
    #[Route('/ordonnance/ajouterjson/new',name:'app_ordonnance_ajouter_json')]
    public function ajouterJson(ConsultationRepository $consultationrepo, Request $request,NormalizerInterface $normalizerInterface)
    {
        $consultation = $consultationrepo->find($request->get('consultation'));
        $em = $this->getDoctrine()->getManager();
        $ordonnance = new Ordonnance();
        $ordonnance->setConsultation($consultation);
        $ordonnance->setValidite($request->get('validite'));
        $em->persist($ordonnance);
        $em->flush();
        $jsonContent = $normalizerInterface->normalize($ordonnance,'json',['groups'=>'ordonnances']);
        return new Response(json_encode($jsonContent));
    }
    #[Route('/ordonnance/modifierjson/{reference}',name:'app_ordonnance_modifier_json')]
    public function modifierJson(Request $request,$reference,NormalizerInterface $normalizerInterface,ConsultationRepository $consultationRepository)
    {
        $consultation = $consultationRepository->find($request->get('consultation'));
        $em = $this->getDoctrine()->getManager();
        $ordonnance = $em->getRepository(Ordonnance::class)->find($reference);
        $ordonnance->setConsultation($consultation);
        $ordonnance->setValidite($request->get('Validite'));
        $em->persist($ordonnance);
        $em->flush();
        $jsonContent = $normalizerInterface->normalize($ordonnance,'json',['groups'=>'ordonnances']);
        return new Response(json_encode($jsonContent));
    }
    #[Route('/ordonnance/supprimerjson/{reference}',name:'app_ordonnance_supprimer_json')]
    public function supprimerJson(Request $req,$reference,NormalizerInterface $normalizerInterface)
    {
        $em = $this->getDoctrine()->getManager();
        $ordonnance = $em->getRepository(Ordonnance::class)->find($reference);
        $em->remove($ordonnance);
        $em->flush();
        $jsonContent = $normalizerInterface->normalize($ordonnance,'json',['groups'=>'ordonnances']);
        return new Response("Ordonnance Supprimée".json_encode($jsonContent));
    }

}
