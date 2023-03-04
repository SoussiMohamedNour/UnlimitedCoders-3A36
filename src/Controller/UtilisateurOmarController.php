<?php

namespace App\Controller;

use App\Entity\UtilisateurOmar;
use App\Form\UtilisateurOmarType;
use App\Repository\UtilisateurOmarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateur/omar')]
class UtilisateurOmarController extends AbstractController
{
    #[Route('/', name: 'app_utilisateur_omar_index', methods: ['GET'])]
    public function index(UtilisateurOmarRepository $utilisateurOmarRepository): Response
    {
        return $this->render('utilisateur_omar/index.html.twig', [
            'utilisateur_omars' => $utilisateurOmarRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_omar_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UtilisateurOmarRepository $utilisateurOmarRepository): Response
    {
        $utilisateurOmar = new UtilisateurOmar();
        $form = $this->createForm(UtilisateurOmarType::class, $utilisateurOmar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurOmarRepository->save($utilisateurOmar, true);

            return $this->redirectToRoute('app_utilisateur_omar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur_omar/new.html.twig', [
            'utilisateur_omar' => $utilisateurOmar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_omar_show', methods: ['GET'])]
    public function show(UtilisateurOmar $utilisateurOmar): Response
    {
        return $this->render('utilisateur_omar/show.html.twig', [
            'utilisateur_omar' => $utilisateurOmar,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_omar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UtilisateurOmar $utilisateurOmar, UtilisateurOmarRepository $utilisateurOmarRepository): Response
    {
        $form = $this->createForm(UtilisateurOmarType::class, $utilisateurOmar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurOmarRepository->save($utilisateurOmar, true);

            return $this->redirectToRoute('app_utilisateur_omar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur_omar/edit.html.twig', [
            'utilisateur_omar' => $utilisateurOmar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_omar_delete', methods: ['POST'])]
    public function delete(Request $request, UtilisateurOmar $utilisateurOmar, UtilisateurOmarRepository $utilisateurOmarRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateurOmar->getId(), $request->request->get('_token'))) {
            $utilisateurOmarRepository->remove($utilisateurOmar, true);
        }

        return $this->redirectToRoute('app_utilisateur_omar_index', [], Response::HTTP_SEE_OTHER);
    }
}
