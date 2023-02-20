<?php

namespace App\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordonnance;
use App\Form\FormordonnanceType;
use App\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use SebastianBergmann\Environment\Console;

class AfiicherOrdenenceController extends AbstractController
{
    #[Route('/afiicher/ordenence', name: 'app_afiicher_ordenence')]
    public function index(Request $request): Response
    {

        {
            $propertySearch = new Ordonnance();
            $form = $this->createForm(SearchType::class,$propertySearch);
            $form->handleRequest($request);
           //initialement le tableau des articles est vide, 
           //c.a.d on affiche les articles que lorsque l'utilisateur clique sur le bouton rechercher
            $articles= [];
            
           if($form->isSubmitted() && $form->isValid()) {
           //on récupère le code d'article tapé dans le formulaire
            $code = $propertySearch->getcode();   
            
            if ($code!="") 
              //si on a fourni un code d'article on affiche tous les Ordonnance ayant ce code
              
              $articles= $this->getDoctrine()->getRepository(Ordonnance::class)->findBy(['code' => $code] );//doctrineeeee 

           }
            return  $this->render('afiicher_ordenence/index.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]);  
          }

       
}
}