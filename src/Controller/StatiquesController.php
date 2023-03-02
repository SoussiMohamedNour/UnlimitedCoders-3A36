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
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart  as ChartjsChart ;

class StatiquesController extends AbstractController
{
    private $utilisateurRepository;
    private $rendezVousRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository, RendezVousRepository $rendezVousRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->rendezVousRepository = $rendezVousRepository;
    }

    #[Route('/stat/{id}', name: 'app_statiques', methods: ['GET', 'POST', 'HEAD'])]
    public function index(Request $request, UtilisateurRepository $utilisateurRepository, RendezVousRepository $rendezVousRepository ,$id, ChartBuilderInterface $chartBuilder): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

     /*   $form = $this->getForm($request);*/
        $appointmentsData = $rendezVousRepository->getNumberOfAppointmentsPerDay($utilisateur, $startDate, $endDate);
        dump($appointmentsData);


        $chart = $chartBuilder->createChart(ChartjsChart::TYPE_LINE);
        $chart->setData([
            'labels' => array_keys((array)$appointmentsData),
            'datasets' => [
                [
                    'label' => 'Number of Appointments',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'data' => array_values((array)$appointmentsData),
                ],
            ],
        ]);
        $chart->setOptions([
            'responsive' => true,

        ]);
        dump($chart);
        return $this->render('statiques/index.html.twig', [
            'utilisateur' => $utilisateur,
            'numberOfAppointments' => $appointmentsData,
            'chart' => $chart ,

        ]);
    }


    #[Route('/submit/{id}', name: 'app_submit_form', methods: ['POST', 'GET', 'HEAD'])]
    public function submitForm(Request $request, $id): Response
    {
        $form = $this->getForm($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $startDate = $formData['startDate'];
            $endDate = $formData['endDate'];

            return $this->redirectToRoute('app_statiques', [
                'id' => $id,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'form' => $form,

            ]);
        }

        return $this->render('statiques/submitForm.html.twig', [
            'form' => $form->createView(),
            'id' => $id,

        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm(Request $request): \Symfony\Component\Form\FormInterface
    {
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
        return $form;
    }
}





