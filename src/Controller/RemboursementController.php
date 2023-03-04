<?php

namespace App\Controller;

use App\Service\Mailer;
use App\Form\MailerType;
use App\Entity\Remboursement;
use App\Form\RemboursementType;
use Symfony\Component\Mime\Email;
use App\Repository\RemboursementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/backoffice')]
class RemboursementController extends AbstractController
{
    
    #[Route('/remboursement', name: 'app_remboursement_index', methods: ['GET'])]
    public function index(Request $request,RemboursementRepository $remboursementRepository, PaginatorInterface $paginator): Response
    {   $data = $remboursementRepository->findAll();

        $remboursements = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), // Current page number. If none is specified, default to 1.
            1 // Number of items to display per page
        );
    
        return $this->render('remboursement/index.html.twig', [
            'remboursements' => $remboursements,
        ]);
    }
    

    #[Route('/remboursement/new', name: 'app_remboursement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RemboursementRepository $remboursementRepository, MailerInterface $mailer): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $dep=$remboursementRepository->getAllDepotById($form->get('depot')->getData()->getIdDossier());
            
            $fiche=$dep[0]->getFiche();

            $idFiche=$fiche->getIdFiche();

            $getFiche=$remboursementRepository->getFicheById($idFiche);

            $montantConsultation=$getFiche[0]->getMontantConsultation();
            $montantMedecament=$getFiche[0]->getMontantMedicaments();

            $somm= ($montantConsultation*0.7)+($montantMedecament*0.1); 
            $remboursement->setMontantRembourse($somm);

            $remboursementRepository->save($remboursement, true);
            // Après l'enregistrement du remboursement
            $patient = $remboursement->getDepot()->getPatient();
            $montant = $remboursement->getMontantRembourse();
            $subject = 'Notification de remboursement';
            $message = "Bonjour " . $patient->getNom() .$patient->getPrenom(). ",\n\nNous vous informons que le montant de votre remboursement est de " . $montant . " Dinars.\n\nCordialement,\nL'équipe de CNAM.";

            $email = (new Email())
                ->from('healthified.consultation.module@gmail.com')
                ->to("takwa.sakouhi@esprit.tn")
                ->subject($subject)
                ->text($message);

            $mailer->send($email);

            return $this->redirectToRoute('app_remboursement_index', [], Response::HTTP_SEE_OTHER);
        }
       
        return $this->renderForm('remboursement/new.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form,
        ]);
    }
    
    #[Route('/remboursement/{id}', name: 'app_remboursement_show', methods: ['GET'])]
    public function show(Remboursement $remboursement): Response
    {
        return $this->render('remboursement/show.html.twig', [
            'remboursement' => $remboursement,
        ]);
    }

    #[Route('/remboursement/{id}/edit', name: 'app_remboursement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Remboursement $remboursement, RemboursementRepository $remboursementRepository): Response
    {
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $remboursementRepository->save($remboursement, true);

            return $this->redirectToRoute('app_remboursement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('remboursement/edit.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form,
        ]);
    }

    #[Route('/remboursement/{id}', name: 'app_remboursement_delete', methods: ['POST'])]
    public function delete(Request $request, Remboursement $remboursement, RemboursementRepository $remboursementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$remboursement->getId(), $request->request->get('_token'))) {
            $remboursementRepository->remove($remboursement, true);
        }

        return $this->redirectToRoute('app_remboursement_index', [], Response::HTTP_SEE_OTHER);
    }
}
