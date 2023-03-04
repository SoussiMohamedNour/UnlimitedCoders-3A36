<?php

namespace App\Controller;

use App\Entity\UtilisateurMouheb;
use App\Form\UtilisateurMouhebType;
use App\Repository\UtilisateurMouhebRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateurmouheb')]
class UtilisateurMouhebController extends AbstractController
{
    #[Route('/', name: 'app_utilisateurmouheb_index', methods: ['GET'])]
    public function index(UtilisateurMouhebRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur_mouheb/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_utilisateurmouheb_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UtilisateurMouhebRepository $utilisateurRepository): Response
    {
        $utilisateur = new UtilisateurMouheb();
        $form = $this->createForm(UtilisateurMouhebType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateurmouheb_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur_mouheb/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateurmouheb_show', methods: ['GET'])]
    public function show(UtilisateurMouheb $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateurmouheb_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UtilisateurMouheb $utilisateur, UtilisateurMouhebRepository $utilisateurRepository): Response
    {
        $form = $this->createForm(UtilisateurMouhebType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateurmouheb_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateurmouheb_delete', methods: ['POST'])]
    public function delete(Request $request, UtilisateurMouheb $utilisateur, UtilisateurMouhebRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur, true);
        }

        return $this->redirectToRoute('app_utilisateurmouheb_index', [], Response::HTTP_SEE_OTHER);
    }

}