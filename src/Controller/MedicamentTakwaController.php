<?php

namespace App\Controller;

use App\Entity\MedicamentTakwa;
use App\Form\MedicamenttakwaType;
use App\Repository\MedicamentTakwaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice')]
class MedicamentTakwaController extends AbstractController
{
    #[Route('/medicamenttakwa', name: 'app_medicament_index', methods: ['GET'])]
    public function index(MedicamentTakwaRepository $medicamentRepository): Response
    {
        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medicamentRepository->findAll(),
        ]);
    }

    #[Route('/medicamenttakwa/new', name: 'app_medicament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MedicamentTakwaRepository $medicamentRepository): Response
    {
        $medicament = new MedicamentTakwa();
        $form = $this->createForm(MedicamenttakwaType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medicamentRepository->save($medicament, true);

            return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('medicament/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
        ]);
    }

    #[Route('/medicamenttakwa/{id}', name: 'app_medicament_show', methods: ['GET'])]
    public function show(MedicamentTakwa $medicament): Response
    {
        return $this->render('medicament/show.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    #[Route('/medicamenttakwa/{id}/edit', name: 'app_medicament_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MedicamentTakwa $medicament, MedicamentTakwaRepository $medicamentRepository): Response
    {
        $form = $this->createForm(MedicamenttakwaType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medicamentRepository->save($medicament, true);

            return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('medicament/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
        ]);
    }

    #[Route('/medicamenttakwa/{id}', name: 'app_medicament_delete', methods: ['POST'])]
    public function delete(Request $request, MedicamentTakwa $medicament, MedicamentTakwaRepository $medicamentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medicament->getId(), $request->request->get('_token'))) {
            $medicamentRepository->remove($medicament, true);
        }

        return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
    }
}