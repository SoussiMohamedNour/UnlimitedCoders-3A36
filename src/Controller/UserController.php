<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;


class UserController extends AbstractController
{
    // #[Route('/user', name: 'app_user')]
    // public function index(): Response
    // {
    //     return $this->render('utilisateur/index.html.twig', [
    //         'controller_name' => 'UserController',
    //     ]);
    // }

   


    #[Route('/backofficemed', name: 'app_medecin')]
    public function home(): Response
    {
        return $this->render('BackOffice/medecin.html.twig');
    }

    #[Route('/backofficeadmin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('BackOffice/admin.html.twig');
    }

    #[Route('/backofficeph', name: 'app_pharmacien')]
    public function index1(): Response
    {
        return $this->render('BackOffice/pharmacien.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }


    #[Route('/frontoffice', name: 'app_home')]
    public function index2(): Response
    {
        return $this->render('Frontoffice/base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }


    #[Route('/user', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index3(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }


    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        return $this->redirectToRoute('login/index.html.twig');
    }

    #[Route('user/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('user/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('user/{id}/delete', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur, true);
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
