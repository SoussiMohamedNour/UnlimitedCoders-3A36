<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/showFront.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository,ArticleRepository $repo,UserRepository $userRepository): Response
    {
        $commentaires = $this->getDoctrine()
        ->getRepository(Commentaire::class)
        ->findByArticle($commentaire->getArticle());
        $articles = $repo->trie3();

        $articlesnblike=$repo->trienblike();
        $user=$userRepository->find(1);
        $favoris=$user->getArticles();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_article_showfront', ['id'=>$commentaire->getArticle()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
            'article' => $commentaire->getArticle(),
            'articles' => $articles,
            'articlesnblike'=>$articlesnblike,
            'commentaires' => $commentaires,
            'user'=>$user,
            'favoris'=>$favoris,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $nb=$commentaire->getArticle()->getNbcomment()-1;
            $commentaire->getArticle()->setNbcomment($nb);
            $commentaireRepository->remove($commentaire, true);
        }

        return $this->redirectToRoute('app_article_show', ['id'=>$commentaire->getArticle()->getId()], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/delete', name: 'app_commentaire_delete2', methods: ['POST'])]
    public function delete2(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $nb=$commentaire->getArticle()->getNbcomment()-1;
            $commentaire->getArticle()->setNbcomment($nb);
            $commentaireRepository->remove($commentaire, true);
            $sid = "ACe1cd2a51605dffa8d548b236e7266044"; // Your Account SID from www.twilio.com/console
            $token = "1664b9894b97f04097c0a26ee5339b58"; // Your Auth Token from www.twilio.com/console
            $description='Cher client votre commentaire est supprimé par notre administrateur car il contient des mots censorisés';
            $client = new Client($sid, $token);
            $message = $client->messages->create(
                '+21699170951', // Text this number
                [
                    'from' => '+15676676341', // From a valid Twilio number
                    'body' => $description
                ]
            );
        }

        return $this->redirectToRoute('app_article_show', ['id'=>$commentaire->getArticle()->getId()], Response::HTTP_SEE_OTHER);
    }
}
