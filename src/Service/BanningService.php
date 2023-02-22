<?php
namespace App\Service;


use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class banService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function banUser(Utilisateur $user)
    {
        $user->setIsbanned(true);
        $roles = $user->getRoles();
        $roles[] = 'ROLE_BANNED';
        $user->setRoles(array_unique($roles));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
