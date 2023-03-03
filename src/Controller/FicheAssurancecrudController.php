<?php

namespace App\Controller;

use App\Entity\FicheAssurance;
use App\Form\FicheAssuranceType;
use App\Repository\FicheAssuranceRepository;
use Doctrine\ORM\Mapping\Id;
use App\Entity\Facteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fiche/assurancecrud')]
class FicheAssurancecrudController extends AbstractController
{
    #[Route('/', name: 'app_fiche_assurancecrud_index', methods: ['GET'])]
    public function index(FicheAssuranceRepository $ficheAssuranceRepository): Response
    {
        return $this->render('fiche_assurancecrud/index.html.twig', [
            'fiche_assurances' => $ficheAssuranceRepository->findAll(),
        ]);
    }

    #[Route('/statistique',name:'app_ordonnance_statistique', methods: ['GET'])]
    public function statistique(FicheAssuranceRepository $ficheAssuranceRepository): Response
    {

        $ficheAssurance = $ficheAssuranceRepository->total_ficheAssurance();
      
        $ficheAssuranceé = $ficheAssuranceRepository->total_ficheAssuranceé();
       
        return $this->render('/fiche_assurancecrud/statistique.html.twig',[
            'ficheAssurance'=>json_encode($ficheAssurance),
            'ficheAssuranceé'=>json_encode($ficheAssuranceé)
        ]);
}

    #[Route('/new', name: 'app_fiche_assurancecrud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FicheAssuranceRepository $ficheAssuranceRepository,MailerInterface $mailer  ): Response
    {
        $cin= $request->query->get('cin', '');
        $nom = $request->query->get('nom', '');
        $ficheAssurance = new FicheAssurance();
        $form = $this->createForm(FicheAssuranceType::class, $ficheAssurance, [
            'cin' => $cin,
            'nom' => $nom,
         ]); 

        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $ficheAssuranceRepository->save($ficheAssurance, true);
            $email = (new Email())
            ->from('ahmed.ridha199@esprit.tn' )
            ->to('ahmed.ridha199@esprit.tn')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
    
             $mailer->send($email);
            return $this->redirectToRoute('app_fiche_assurancecrud_index', [], Response::HTTP_SEE_OTHER);
        }
       
        return $this->renderForm('fiche_assurancecrud/new.html.twig', [
            'fiche_assurance' => $ficheAssurance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_assurancecrud_show', methods: ['GET'])]
    public function show(FicheAssurance $ficheAssurance): Response
    {
        return $this->render('fiche_assurancecrud/show.html.twig', [
            'fiche_assurance' => $ficheAssurance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_assurancecrud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FicheAssurance $ficheAssurance, FicheAssuranceRepository $ficheAssuranceRepository): Response
    {
        $form = $this->createForm(FicheAssuranceType::class, $ficheAssurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheAssuranceRepository->save($ficheAssurance, true);

            return $this->redirectToRoute('app_fiche_assurancecrud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche_assurancecrud/edit.html.twig', [
            'fiche_assurance' => $ficheAssurance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_assurancecrud_delete', methods: ['POST'])]
    public function delete(Request $request, FicheAssurance $ficheAssurance, FicheAssuranceRepository $ficheAssuranceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheAssurance->getId(), $request->request->get('_token'))) {
            $ficheAssuranceRepository->remove($ficheAssurance, true);
        }

        return $this->redirectToRoute('app_fiche_assurancecrud_index', [], Response::HTTP_SEE_OTHER);
    }
  
    
}