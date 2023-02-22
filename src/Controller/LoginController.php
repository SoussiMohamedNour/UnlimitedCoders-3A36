<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Utilisateur;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UtilisateurRepository;
use App\Repository\NotificationRepository;
use PhpParser\Builder\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;




class LoginController extends AbstractController

{
    


    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }


    #[Route('/reset-password',name:'app_resetpw')]
    public function sendMail(MailerInterface $mailer,Request $request)
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        $email = (new Email());
        if($form->isSubmitted() && $form->isValid())
        {
            $from = 'healthified.consultation.module@gmail.com';
            $to = $form->get('email')->getData();
            $subject = 'Password Reset';
            $email->from($from)
            ->to($to)
            ->subject($subject);
        }
        $mailer->send($email);
        return $this->renderForm('reset_password/request.html.twig',['requestForm'=>$form]);
}

}