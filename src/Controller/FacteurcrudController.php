<?php

namespace App\Controller;

use App\Entity\Facteur;
use App\Form\FacteurType;
use App\Repository\FacteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/facteurcrud')]
class FacteurcrudController extends AbstractController
{
    #[Route('/', name: 'app_facteurcrud_index', methods: ['GET'])]
    public function index(FacteurRepository $facteurRepository): Response
    {
        return $this->render('facteurcrud/index.html.twig', [
            'facteurs' => $facteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_facteurcrud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FacteurRepository $facteurRepository): Response
    {
        $facteur = new Facteur();
        $form = $this->createForm(FacteurType::class, $facteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facteurRepository->save($facteur, true);

            return $this->redirectToRoute('app_facteurcrud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facteurcrud/new.html.twig', [
            'facteur' => $facteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facteurcrud_show', methods: ['GET'])]
    public function show(Facteur $facteur): Response
    {
        return $this->render('facteurcrud/show.html.twig', [
            'facteur' => $facteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facteurcrud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facteur $facteur, FacteurRepository $facteurRepository): Response
    {
        $form = $this->createForm(FacteurType::class, $facteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facteurRepository->save($facteur, true);

            return $this->redirectToRoute('app_facteurcrud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facteurcrud/edit.html.twig', [
            'facteur' => $facteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facteurcrud_delete', methods: ['POST'])]
    public function delete(Request $request, Facteur $facteur, FacteurRepository $facteurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facteur->getId(), $request->request->get('_token'))) {
            $facteurRepository->remove($facteur, true);
        }

        return $this->redirectToRoute('app_facteurcrud_index', [], Response::HTTP_SEE_OTHER);
    }
}
