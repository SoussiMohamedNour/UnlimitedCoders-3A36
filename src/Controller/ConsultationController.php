<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice')]
class ConsultationController extends AbstractController
{
    #[Route('/consultation', name: 'app_consultation_index', methods: ['GET'])]
    public function index(ConsultationRepository $consultationRepository): Response
    {
        return $this->render('BackOffice/consultation/index.html.twig', [
            'consultations' => $consultationRepository->findAll(),
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

    #[Route('/consultation/{reference}', name: 'app_consultation_show', methods: ['GET'])]
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
}
