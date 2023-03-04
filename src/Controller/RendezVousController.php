<?php

namespace App\Controller;


use App\Entity\RendezVous;
use App\Entity\Utilisateur;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




#[Route('/rendez/vous')]
class RendezVousController extends AbstractController
{
    #[Route('/', name: 'app_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }

    #[Route('/new/{patient}', name: 'app_rendez_vous_new', methods: ['GET', 'POST', 'HEAD'])]
    public function new(Request $request, RendezVousRepository $rendezVousRepository, $patient, UtilisateurRepository $utilisateurRepository,EntityManagerInterface $entityManager): Response
    {
        $rendezVous = new RendezVous();
        $utilisateur = $utilisateurRepository->find($patient);
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVous = $form->getData();
            $rendezVous->setPatient($utilisateur);
            $rendezVousRepository->save($rendezVous, true);
            return $this->redirectToRoute('app_rendezvous_pdf', ['patient' => $patient], Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('rendez_vous/new.html.twig', [
            'rendez_vou' => $rendezVous,
            'form' => $form,
            'patient' => $utilisateur,
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVousRepository->save($rendezVou, true);

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rendezVou->getId(), $request->request->get('_token'))) {
            $rendezVousRepository->remove($rendezVou, true);
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{patient}/pdf', name: 'app_rendezvous_pdf', methods: ['GET'])]
    public function rendezvousPdf(RendezVous $rendezVous ,$patient): Response
    {
        // Create a new instance of Dompdf
        $dompdf = new Dompdf();

        // Generate the PDF content by rendering a Twig template
        $html = $this->renderView('rendez_vous/pdf.html.twig', [
            'rendezVous' => $rendezVous,
        ]);

        // Load the HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set the paper size and orientation (optional)
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF content
        $dompdf->render();

        // Generate the response object with the PDF content
        $response = new Response($dompdf->output());

        // Set the headers to force download the PDF file
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="rendez-vous.pdf"');
        $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');

        return $response;
    }

}
