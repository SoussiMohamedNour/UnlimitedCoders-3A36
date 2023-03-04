<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;

use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use MercurySeries\FlashyBundle\SymfonySessionStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Snipe\BanBuilder\CensorWords;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, NormalizerInterface $Normalizer, PaginatorInterface $paginator, Request $request): Response
    {
        $donnees = $articleRepository->findAll();
        $articles=$paginator->paginate(
            $donnees,// Requête contenant les données à paginer (ici les articles)
            $request->query->getInt('page',1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3   // Nombre de résultats par page
        );
        // $jsoncontent =$Normalizer->normalize($articles,'json',['Groups'=>'articles:read']);
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            //json_encode($jsoncontent),
        ]);
    }

    #[Route('/indexfrontarticle', name: 'app_article_indexfront', methods: ['GET'])]
    public function indexFront(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $donnees = $articleRepository->findAll();
        $articles = $paginator->paginate(
            $donnees,// Requête contenant les données à paginer (ici les articles)
            $request->query->getInt('page', 1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3   // Nombre de résultats par page
        );
        return $this->render('article/indexFront.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository, FlashyNotifier $flashy): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $article->setArticleDate(new \DateTime('now'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);
            dump($article->getId());
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
    {
        $commentaires = $this->getDoctrine()
            ->getRepository(Commentaire::class)
            ->findByArticle($article);
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentaires' => $commentaires
        ]);
    }

    #[Route('/articlefront/{id}', name: 'app_article_showfront', methods: ['GET','POST'])]
    public function showFront(Article $article,Request $request, FlashyNotifier $flashy, ArticleRepository $repo,UserRepository $userRepository): Response
    {

        $commentaires = $this->getDoctrine()
            ->getRepository(Commentaire::class)
            ->findByArticle($article);//hedhouma les commentaires elli tebiin l'articel
        $articles = $repo->trie3();
        $articlesnblike=$repo->trienblike();
        //$user=$this->get('security.token_storage')->getToken()->getUser(); getConnectedUser fel integration
        $user=$userRepository->find(1);
        $favoris=$user->getArticles();//hedhouma les articles favoris mtaa l connected user
        $commentaire = new Commentaire($article); //hedha l commentaire elli bech tzidou
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $commentaire->setCommentaireDate(new \DateTime('now'));
        $form->handleRequest($request);
        $nb = $article->getNbcomment();

        if ($form->isSubmitted() && $form->isValid()) {
            $nb++;
            $article->setNbcomment($nb);
            $repo->save($article,true);
            $censor = new CensorWords;
            $langs = array('fr', 'it', 'en-us', 'en-uk', 'de', 'es', 'tn');
            $badwords = $censor->setDictionary($langs);
            $censor->setReplaceChar("*");
            $string = $censor->censorString($commentaire->getCommentairecontenu());
            $commentaire->setCommentairecontenu($string['clean']);
            // $commentaire->getUsers()->add($user); fel integration
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();
            if( strpos($commentaire->getCommentairecontenu(),"*") !== false){
                $flashy->error('Attention votre commentaire contient des mots censorisé','http://your-awesome-link.com');
                return $this->redirectToRoute('app_article_showfront', ['id' => $article->getId()]);
            }
            else {
                $flashy->success('Commentaire Ajouté!', 'http://your-awesome-link.com');
                return $this->redirectToRoute('app_article_showfront', ['id' => $article->getId()]);
            }

        }
        return $this->render('article/showFront.html.twig', [
            'article' => $article,
            'articles' => $articles,
            'articlesnblike'=>$articlesnblike,
            'favoris'=>$favoris,
            'commentaire' => $commentaire,
            'form' => $form->createView(),
            'commentaires' => $commentaires,
            'user'=>$user,
        ]);
    }
    #[Route('/s/stats',name: 'app_article_stats')]
    public function chartjs(ArticleRepository $repository)
    {
        $articles=$repository->findbyNbcomment();
        $nbcomment=[];
        $articleCount=[];
        foreach($articles as $article){
            $nbcomment[] = $article['nbcomment'];
            $articleCount[] = 'Article'.$article['id'];
        }
        return $this->render('article/stats.html.twig', [
            'articleCount' => json_encode($articleCount),
            'nbcomment'=>json_encode($nbcomment),
        ]);
    }


    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository, FlashyNotifier $flashy): Response
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
    public function delete(Request $request, Article $article, FlashyNotifier $flashy, ArticleRepository $articleRepository, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $commentlist = $commentaireRepository->findAll();
            foreach ($commentlist as $comment) {
                $commentaireRepository->remove($comment);
            }
            $articleRepository->remove($article, true);


        }
        $flashy->success('Article Supprimé!', 'http://your-awesome-link.com');
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/s/search',name:'app_article_search')]
    public function searchrecbyTitre(Request $request, NormalizerInterface $Normalizer, ArticleRepository $repository): Response
    {

        $requestString = $request->get('search');
        $articles = $repository->findrecByarticleTitle($requestString);
        dump($articles);
        $jsonContent = $Normalizer->normalize($articles, 'json', ['Groups' => 'articles:read', 'MAX_DEPTH' => '1']);
        $retour = json_encode($jsonContent);
        return new Response($retour);

    }

    #[Route('/addfavori/{id}',name:'app_add_favoris')]
    public function addFavoriteArticle(Request $request, Article $article,UserRepository $userRepository): Response
    {
        //$user=$this->get('security.token_storage')->getToken()->getUser(); getConnectedUser
        $user = $userRepository->find(1);

        if (!$user->getArticles()->contains($article)) {
            $user->addFavorisArticle($article);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('app_article_showfront', ['id' => $article->getId()]);
    }
    #[Route('/remove/{id}',name:'app_remove_favoris')]
    public function removeFavoriteArticle(Request $request, Article $article,UserRepository $userRepository): Response
    {    //$user=$this->get('security.token_storage')->getToken()->getUser(); getConnectedUser
        $user = $userRepository->find(1);
        dump($user->getArticles());
        if ($user->getArticles()->contains($article)) {
            $user->removeFavorisArticle($article,$user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('app_article_showfront', ['id' => $article->getId()]);
    }


    #[Route('/likearticle/{id}',name:'app_article_like')]
    public function likeArticle(Article $article, ArticleRepository $articleRepository)
    {

        $nb = $article->getNblike() + 1;
        $article->setNblike($nb);
        $articleRepository->save($article, true);
         return $this->redirectToRoute('app_article_showfront', ['id' => $article->getId()]);
    }

    #[Route('/dislikearticle/{id}',name:'app_article_dislike')]
    public function dislikeArticle(Article $article, ArticleRepository $articleRepository)
    {
        $nb = $article->getNbdislike() + 1;
        $article->setNbdislike($nb);
        $articleRepository->save($article,true);
         return $this->redirectToRoute('app_article_showfront', ['id' => $article->getId()]);
    }

}