<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserApiController extends AbstractController
{
    #[Route('/api/Register', name: 'api_Register')]
    public function Register(UtilisateurRepository $repository ,ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $passwordHasher,SerializerInterface $serializer)
    {   
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $user = new Utilisateur();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return new Response("email invalid.");
        }


        
        $user->setRoles(['ROLE_USER']);
        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setPassword($hashedPassword);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new JsonResponse("account is created", 200);
        }catch(\Exception $ex){
            return new Response("execption".$ex->getMessage());
        }
    }
    
    
    
    
    /*#[Route('/api/Register', name: 'api_Register')]
    public function Register(UserRepository $repository ,ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $passwordHasher,SerializerInterface $serializer)
    {
        $jsonRecu = $request->getContent();
        $inputs = $serializer->deserialize($jsonRecu, User::class, 'json');
        $user = new User();
        $plaintextPassword = $inputs->getPassword();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setRoles(['ROLE_CLIENT']);
        $user->setApproved(true);
        $user->setIsCoach(false);
        $user->setEmail($inputs->getEmail());
        $user->setNom("aziz");
        $user->setPrenom("aziz");
        //$user->setNom($inputs->getPrenom());
        //$user->setPrenom($inputs->getNom());
        $user->setPassword($hashedPassword);
        $repository->save($user, true);
        return  $this->json('Creation avec succes', 201, []);
    }*/

    #[Route('/api/moblog', name: 'api_loginn')]
    public function Login(Request $request){
        $email = $request->query->get("email");
        $password = $request->query->get("password");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Utilisateur::class)->findOneBy(['email'=>$email]);
        if($user){
            if(password_verify($password,$user->getPassword())){
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else{
                return new Response("Not Found Password");
            }
        }
        else{
            return new Response("User Not Found");
        }
    }

    #[Route('/api/editProfile', name: 'api_editProfile')]
    public function editProfile(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $id = $request->get("id");
        $email = $request->query->get("email");
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $password = $request->query->get("password");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if($request->files->get("image")!=null){
            $file = $request->files->get("image");
            $fileName = $file->getClientOriginalName();

            $file->move(
                $fileName
            );
            $user->setImage($fileName);
        }
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $password
            )
        );
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
        $user->setApproved(true);
        $user->setIsCoach(false);

        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            return new JsonResponse("Success", 200);
        }catch(\Exception $ex){
            return new Response("fail".$ex->getMessage());
        }
    }


    #[Route('/api/getPasswordByEmail', name: 'api_password')]
    public function getPasswordByEmail(Request $request){

        $email = $request->get('email');
        $user = $this->getDoctrine()->getManager()->getRepository(Utilisateur::class)->findOneBy(['email'=>$email]);
        if($user){
            $password = $user->getPassword();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($password);
            return new JsonResponse($formatted);
        }
        return new Response("user not found");
    }
}
