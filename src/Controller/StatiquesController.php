<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UtilisateurRepository;
use App\Repository\RendezVousRepository;

class StatiquesController extends AbstractController
{
    private $utilisateurRepository;
    private $rendezVousRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository, RendezVousRepository $rendezVousRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->rendezVousRepository = $rendezVousRepository;
    }
     #[Route('/stat/{id}', name: 'app_statiques' ,methods: ['GET', 'POST', 'HEAD'])]
     public function index(Request $request, UtilisateurRepository $utilisateurRepository, RendezVousRepository $rendezVousRepository, $id): Response
     {
         $utilisateur = $utilisateurRepository->find($id);
         $numberOfAppointments = 0;

         $form = $this->createFormBuilder()
             ->add('startDate', DateType::class, [
                 'label' => 'Start Date',
                 'widget' => 'single_text',
                 'attr' => ['class' => 'form-control'],
                 'required' => true,
                 'input_format' => 'Y-m-d',
             ])
             ->add('endDate', DateType::class, [
                 'label' => 'End Date',
                 'widget' => 'single_text',
                 'attr' => ['class' => 'form-control'],
                 'required' => true,
                 'input_format' => 'Y-m-d',
             ])
             ->add('submit', SubmitType::class, [
                 'label' => 'Submit',
                 'attr' => ['class' => 'btn btn-primary'],
             ])
             ->getForm();

         $form->handleRequest($request);
         $appointmentsData = [];
         if ($form->isSubmitted() && $form->isValid()) {
             $formData = $form->getData();
             $startDate = $formData['startDate'];
             $endDate = $formData['endDate'];

             $numberOfAppointments = $rendezVousRepository->getNumberOfAppointmentsForDoctor($utilisateur, $startDate, $endDate);
             $appointmentsData = $rendezVousRepository->getNumberOfAppointmentsPerDay($utilisateur, $startDate, $endDate);

         }

         return $this->render('statiques/index.html.twig', [
             'utilisateur' => $utilisateur,
             'numberOfAppointments' => $numberOfAppointments,
             'appointmentsData'=> $appointmentsData,
             'form' => $form->createView()
         ]);
     }
}
