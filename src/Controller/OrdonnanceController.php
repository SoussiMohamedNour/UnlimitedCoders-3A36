<?php

namespace App\Controller;

use App\Entity\Ordonnance;
use App\Form\MailerType;
use App\Form\OrdonnanceType;
use App\Repository\OrdonnanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/backoffice')]
class OrdonnanceController extends AbstractController
{
    #[Route('/ordonnance', name: 'app_ordonnance_index', methods: ['GET'])]
    public function index(OrdonnanceRepository $ordonnanceRepository): Response
    {
        return $this->render('BackOffice/ordonnance/index.html.twig', [
            'ordonnances' => $ordonnanceRepository->findAll(),
        ]);
    }

    #[Route('/ordonnance/ajouter', name: 'app_ordonnance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OrdonnanceRepository $ordonnanceRepository): Response
    {
        $ordonnance = new Ordonnance();
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ordonnanceRepository->save($ordonnance, true);

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
            $email->from($from)
            ->to($to)
            ->subject($subject)
            ->text($text);
        }
        $mailer->send($email);
        return $this->renderForm('/BackOffice/ordonnance/email.html.twig',['form'=>$form]);
    }
}
