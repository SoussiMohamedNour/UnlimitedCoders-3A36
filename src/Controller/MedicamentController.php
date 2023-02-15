<?php

namespace App\Controller;

use App\Entity\Medicament;
use App\Form\MedicamentType;
use App\Repository\MedicamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/medicament/{id}', name: 'app_medicament_show', methods: ['GET'])]
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
}
