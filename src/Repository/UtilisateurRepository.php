<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function save(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }



    // /**
    //  * @Route("/banned", name="banUtilisateur")
    //  */

    // public function banUser(Utilisateur $utilisateur): void

    
    // {
    //     $entityManager = $this->getEntityManager();
        
    //     $userRoles = $utilisateur->getRoles();
    //     if (!in_array('ROLE_BANNED', $userRoles)) {
    //         $userRoles[] = 'ROLE_BANNED';
    //         $utilisateur->setRoles($userRoles);
    //     }
    //     $utilisateur->setIsbanned(true);
    //     $entityManager->flush();
    // }


    public function total_utilisateur():float
    {
        $query = $this->createQueryBuilder('c')
        ->select('count(c) as nombre_total_utilisateur')
        // ->from('Utilisateur' , 'c')
        ->getQuery()
        ->getSingleResult();

        return $query['nombre_total_utilisateur'];
    }

    public function total_utilisateur_banned():float
    {
        $query = $this->createQueryBuilder('c')
        ->select('count(c) as nombre_total_utilisateur_banned')
            // ->from('utilisateur' , 'c')
            ->where('c.isbanned = true')
            ->getQuery()
            ->getSingleResult();

        return $query['nombre_total_utilisateur_banned'];
    }

    public function total_utilisateur_unbanned():float
    {
        $query = $this->createQueryBuilder('c')
        ->select('count(c) as nombre_total_utilisateur_unbanned')
            // ->from('utilisateur' , 'c')
            ->where('c.isbanned = false')
            ->getQuery()
            ->getSingleResult();

        return $query['nombre_total_utilisateur_unbanned'];
    }

    }






