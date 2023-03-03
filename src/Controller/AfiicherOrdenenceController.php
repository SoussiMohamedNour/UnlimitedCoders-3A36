<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordonnance;
use App\Form\SearchType;
use App\Repository\OrdonnanceRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\SerializerInterface;

class AfiicherOrdenenceController extends AbstractController
{
    #[Route('/afiicher/ordenence', name: 'app_afiicher_ordenence', methods: ['GET', 'POST'])]
    public function index(Request $request,OrdonnanceRepository $Ordonnance): Response
    {

        {
            $propertySearch = new Ordonnance();
            $form = $this->createForm(SearchType::class,$propertySearch);
            $form->handleRequest($request);
           //initialement le tableau des articles est vide, 
           //c.a.d on affiche les articles que lorsque l'utilisateur clique sur le bouton rechercher
           $sessions = null; 
            
           if($form->isSubmitted() && $form->isValid()) {
           //on récupère le code d'article tapé dans le formulaire
           $code = $propertySearch->getcode();   
           
         if ($code!="") 
              //si on a fourni un code d'article on affiche tous les Ordonnance ayant ce code
              
              $sessions= $Ordonnance->searchByName( $code );//doctrineeeee 

           }
          
           
          
          }
          return  $this->render('afiicher_ordenence/index.html.twig',[ 'form' =>$form->createView(), 'sessions' => $sessions]);
        }
         
       //   #[Route('/afiicher/ordenence', name: 'app_afiicher_ordenence')]
//public function search(Request $request,OrdonnanceRepository $Ordonnance,SerializerInterface $serializer): JsonResponse
         // {
             // $searchTerm = $request->query->get('code', '');
      
             // $code = $Ordonnance->searchByName($searchTerm);
             // $json = $serializer->serialize($code, 'json', ['groups' => ['main'], 'max_depth' => 1]);
             // if (empty($json)) {
                //return new JsonResponse(['message' => 'No Ordonnance found with that code.'], Response::HTTP_NOT_FOUND);
          //  }
           // return $this->json($json); 
        //  }


       //   public function search(Request $request, EntityManagerInterface $entityManager)
         // {
            // $code = $request->query->get('code', '');
          
            // $query = $entityManager->createQuery(
           //   'SELECT o
            //  FROM App\Entity\Ordonnance o
           //   WHERE o.code = :code'
       //   )->setParameter('code', $code);
          
          //  $ordonnance = $query->getResult();
            
          //    $response = new JsonResponse();
            //  $response->setData($ordonnance);
          
           //   return $response;
        //  }
       

         
//public function search(Request $request ,OrdonnanceRepository $Ordonnance): JsonResponse
//{
 // $code = $request->query->get('code', '');
 // $students = $this->getDoctrine()->getRepository(Ordonnance::class)->searchByName();

//return $this->json($students);
//}

  
}
// public function show(Ordonnance $ordonnance ,string $code): Response
    //{
      
        
      //  $ordonnance = $this->getDoctrine()
       // ->getRepository(FormordonnanceType::class)
       // ->findOneBy(['code' => $code]);
       // return $this->render('afiicher_ordenence/show.html.twig', [
        //    'ordonnance' => $ordonnance,
      //  ]);
    //}
