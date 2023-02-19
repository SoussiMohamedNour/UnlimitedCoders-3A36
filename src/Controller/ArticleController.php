<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Repository\ReclamationRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use MercurySeries\FlashyBundle\SymfonySessionStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Snipe\BanBuilder\CensorWords;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $donnees =$articleRepository->findAll();
        $articles=$paginator->paginate(
            $donnees,// Requête contenant les données à paginer (ici les articles)
            $request->query->getInt('page',1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3   // Nombre de résultats par page
        );
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    #[Route('/indexfrontarticle', name: 'app_article_indexfront', methods: ['GET'])]
    public function indexFront(ArticleRepository $articleRepository,PaginatorInterface $paginator,Request $request): Response
    {       $donnees =$articleRepository->findAll();
             $articles=$paginator->paginate(
            $donnees,// Requête contenant les données à paginer (ici les articles)
            $request->query->getInt('page',1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3   // Nombre de résultats par page
        );
        return $this->render('article/indexFront.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository,FlashyNotifier $flashy): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $article->setArticleDate(new \DateTime('now'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);
            $flashy->success('Article Ajouté!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {    $commentaires = $this->getDoctrine()
        ->getRepository(Commentaire::class)
        ->findByArticle($article);
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentaires'=>$commentaires
        ]);
    }
    #[Route('/articlefront/{id}', name: 'app_article_showfront', methods: ['GET','POST'])]
    public function showFront(Article $article,Request $request,FlashyNotifier $flashy,ArticleRepository $repo): Response
    {   $commentaires = $this->getDoctrine()
        ->getRepository(Commentaire::class)
        ->findByArticle($article);
        $articles=$repo->trie3();
        $commentaire = new Commentaire($article);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $commentaire->setCommentaireDate(new \DateTime('now'));
        $form->handleRequest($request);
        $nb=$article->getNbcomment();

        if ($form->isSubmitted() && $form->isValid()) {
            $nb++;
            $article->setNbcomment($nb);
            $this->getDoctrine()->getManager()->flush();
            $censor = new CensorWords;
            $langs = array('fr','it','en-us','en-uk','de','es');
            $badwords = $censor->setDictionary($langs);
            $censor->setReplaceChar("*");
            $string = $censor->censorString($commentaire->getCommentairecontenu());
            $commentaire->setCommentairecontenu($string['clean']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();
            $flashy->success('Commentaire Ajouté!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_article_showfront',['id'=>$article->getId()]);

        }
        return $this->render('article/showFront.html.twig', [
            'article' => $article,
            'articles'=>$articles,
            'commentaire' => $commentaire,
            'form' => $form->createView(),
            'commentaires'=>$commentaires,
        ]);
    }
    /*#[Route('/stats',name: 'app_article_stats')]
    public function chartjs(ArticleRepository $repository)
    {

        $articles = $repository->findAll();

        $dates = [];
        $articlecount = [];
        foreach ($articles as $article) {
            $dates[] = $article['datearticle'];
            $articlecount[] = $article['count'];
        }

        return $this->render('article/stats.html.twig', [
            'dates' => $dates,
            'articlecount' => $articlecount,
        ]);
    }*/


    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);
            $flashy->success('Article Modifié!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article,FlashyNotifier $flashy, ArticleRepository $articleRepository,CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $commentlist=$commentaireRepository->findAll();
            foreach ($commentlist as $comment){
                $commentaireRepository->remove($comment);
            }
            $articleRepository->remove($article, true);


        }
        $flashy->success('Article Supprimé!', 'http://your-awesome-link.com');
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
  /*  #[Route('/search',name: 'app_article_search')]
    public function searchrecbyTitre(Request $request,NormalizerInterface $Normalizer,ArticleRepository $repository):Response
    {

        $requestString=$request->get('searchValue');

        $articles = $repository->findrecByarticleTitle($requestString);
        $jsonContent = $Normalizer->normalize($articles, 'json',['groups'=>'articles:read']);
        $jsonc=json_encode($jsonContent);
        dump($jsonc);
        if(  $jsonc == "[]" )
        {
            return new Response(null);
        }
        else{        return new Response($jsonc);
        }

    }*/
}
