<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class UserController extends AbstractController
{
    

    #[IsGranted('ROLE_MEDECIN')]
    #[Route('/backofficemed', name: 'app_medecin')]
    public function home(): Response
    {
        return $this->render('BackOffice/medecin.html.twig');
    
    }

    // #[Route('/banned', name: 'app_ban')]
    // public function banUser(): Response
    // {
    //     $entityManager = $this->getEntityManager();
    //     $utilisateur=$this->getUser();
    //     $utilisateurRoles=$utilisateur->getRoles();

    //     if (!in_array('ROLE_BANNED', $utilisateurRoles)) {
    //         $userRoles[] = 'ROLE_BANNED';
    //         $utilisateur->setRoles($utilisateurRoles);
    //     }
    //     $utilisateur->setIsbanned(true);
    //     $entityManager->flush();
    //     return $this->render('404/404.html.twig');
    // }

    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/banned', name: 'app_banned')]
    public function banned(): Response
    {
        return $this->render('404/404.html.twig', [
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

    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
    #[Route('user/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
    #[Route('user/{id}/delete', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur, true);
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }


    // Workshop JSON
    #[Route('/index_json',name:'app_user_index_json')]
    public function indexJson(UtilisateurRepository $utilisateurRepository,SerializerInterface $serializerInterface) 
    {
        $utilisateurs = $utilisateurRepository->findAll();
        $json = $serializerInterface->serialize($utilisateurs,'json',['groups'=>'utilisateurs']);
        return new Response($json);
    }
    #[Route('/utilisateur/get/{id}',name:'app_user_get_json')]
    public function recupererJson($id,NormalizerInterface $normalizerInterface,UtilisateurRepository $repo)
    {
        $utilisateur = $repo->find($id);
        $utilisateurnormalizer = $normalizerInterface->normalize($utilisateur,'json',['groups'=>'utilisateurs']);
        return new Response(json_encode($utilisateurnormalizer));
    }
    #[Route('/utilisateur/add_json',name:'app_user_add_json')]
    public function ajouterJson(Request $request,NormalizerInterface $normalizerInterface)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail($request->get('email'));
        $utilisateur->setNom($request->get('nom'));
        $utilisateur->setPrenom($request->get('prenom'));
        $utilisateur->setAge($request->get('age'));
        $utilisateur->setCin($request->get('cin'));
        $utilisateur->setNumTel($request->get('numtel'));
        // $utilisateur->setImage($request->get('image'));
        $utilisateur->setPassword($request->get('plainPassword'));
        $utilisateur->setSexe($request->get('sexe'));

        $em->persist($utilisateur);
        $em->flush();
        
        $jsonContent = $normalizerInterface->normalize($utilisateur,'json',['groups'=>'utilisateurs']);
        return new Response(json_encode($jsonContent));
    }
    #[Route('/utilisateur/modifier/{id}',name:'app_user_modifier_json')]
    public function modifierJson(Request $request,$id,NormalizerInterface $normalizerInterface)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository(Utilisateur::class)->find($id);
        $utilisateur->setEmail($request->get('email'));
        $utilisateur->setNom($request->get('nom'));
        $utilisateur->setPrenom($request->get('prenom'));
        $utilisateur->setAge($request->get('age'));
        $utilisateur->setCin($request->get('cin'));
        $utilisateur->setNumTel($request->get('numtel'));
        // $utilisateur->setImage($request->get('image'));
        $utilisateur->setPassword($request->get('plainPassword'));
        $utilisateur->setSexe($request->get('sexe'));

        $em->flush();
        $jsonContent = $normalizerInterface->normalize($utilisateur,'json',['groups'=>'utilisateurs']);
        return new Response("Utilisateur Modifier avec succees".json_encode($jsonContent));
    }
    #[Route('/utilisateur/supprimer/{id}',name:'app_user_supprimer_json')]
    public function supprimerJson(Request $request,$id,NormalizerInterface $normalizerInterface)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository(Utilisateur::class)->find($id);
        $em->remove($utilisateur);
        $em->flush();
        $jsonContent = $normalizerInterface->normalize($utilisateur,'json',['groups'=>'utilisateurs']);
        return new Response("Utilisateur supprimer avec succees".json_encode($jsonContent));
    }


  

}

