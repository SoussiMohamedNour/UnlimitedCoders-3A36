<?php

namespace App\Controller;

use App\Entity\FicheAssurance;
use App\Form\FicheAssuranceType;
use App\Repository\FicheAssuranceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fiche/assurancecrud')]
class FicheAssurancecrudController extends AbstractController
{
    #[Route('/', name: 'app_fiche_assurancecrud_index', methods: ['GET'])]
    public function index(FicheAssuranceRepository $ficheAssuranceRepository): Response
    {
        return $this->render('fiche_assurancecrud/index.html.twig', [
            'fiche_assurances' => $ficheAssuranceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fiche_assurancecrud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FicheAssuranceRepository $ficheAssuranceRepository): Response
    {
        $ficheAssurance = new FicheAssurance();
        $form = $this->createForm(FicheAssuranceType::class, $ficheAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheAssuranceRepository->save($ficheAssurance, true);

            return $this->redirectToRoute('app_fiche_assurancecrud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche_assurancecrud/new.html.twig', [
            'fiche_assurance' => $ficheAssurance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_assurancecrud_show', methods: ['GET'])]
    public function show(FicheAssurance $ficheAssurance): Response
    {
        return $this->render('fiche_assurancecrud/show.html.twig', [
            'fiche_assurance' => $ficheAssurance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_assurancecrud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FicheAssurance $ficheAssurance, FicheAssuranceRepository $ficheAssuranceRepository): Response
    {
        $form = $this->createForm(FicheAssuranceType::class, $ficheAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheAssuranceRepository->save($ficheAssurance, true);

            return $this->redirectToRoute('app_fiche_assurancecrud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche_assurancecrud/edit.html.twig', [
            'fiche_assurance' => $ficheAssurance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_assurancecrud_delete', methods: ['POST'])]
    public function delete(Request $request, FicheAssurance $ficheAssurance, FicheAssuranceRepository $ficheAssuranceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheAssurance->getId(), $request->request->get('_token'))) {
            $ficheAssuranceRepository->remove($ficheAssurance, true);
        }

        return $this->redirectToRoute('app_fiche_assurancecrud_index', [], Response::HTTP_SEE_OTHER);
    }
}
