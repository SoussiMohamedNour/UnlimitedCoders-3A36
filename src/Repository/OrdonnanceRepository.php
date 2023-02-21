<?php

namespace App\Repository;

use App\Entity\Ordonnance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ordonnance>
 *
 * @method Ordonnance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ordonnance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ordonnance[]    findAll()
 * @method Ordonnance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdonnanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordonnance::class);
    }

    public function save(Ordonnance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ordonnance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    // Tri Reference
    public function getOrdonnanceByReferenceAsc():array{
        return $this->createQueryBuilder('o')
        ->orderBy('o.reference','ASC')
        ->getQuery()
        ->getResult();
    }
    public function getOrdonnanceByReferenceDesc():array{
        return $this->createQueryBuilder('o')
        ->orderBy('o.reference','DESC')
        ->getQuery()
        ->getResult();
    }

    // Tri Validite
    public function getOrdonnanceByValiditeAsc():array{
        return $this->createQueryBuilder('o')
        ->orderBy('o.validite','ASC')
        ->getQuery()
        ->getResult();
    }
    public function getOrdonnanceByValiditeDesc():array{
        return $this->createQueryBuilder('o')
        ->orderBy('o.validite','DESC')
        ->getQuery()
        ->getResult();
    }

    // Tri Id Consultation 
    public function getOrdonnanceByConsultationAsc():array{
        return $this->createQueryBuilder('o')
        ->orderBy('o.consultation','ASC')
        ->getQuery()
        ->getResult();
    }
    public function getOrdonnanceByConsultationDesc():array{
        return $this->createQueryBuilder('o')
        ->orderBy('o.consultation','DESC')
        ->getQuery()
        ->getResult();
    }
    
    public function returnAll():array{
        return $this->findAll();
    }

    public function trier(string $critere,string $ordre):array{
        if($critere == "reference")
        {
            if($ordre == "asc")
            {
                return $this->getOrdonnanceByReferenceAsc();
            }
            else if ($ordre == "desc" )
            {
                return $this->getOrdonnanceByReferenceDesc();
            }

        }
        else if($critere == "validite")
        {
            if($ordre == "asc")
            {
                return $this->getOrdonnanceByValiditeAsc();
            }
            else if ($ordre == "desc")
            {
                return $this->getOrdonnanceByValiditeDesc();
            }
        }
        else if($critere == "idconsultation")
        {
            if($ordre == "asc")
            {
                return $this->getOrdonnanceByConsultationAsc();
            }
            else if($ordre == "desc")
            {
                return $this->getOrdonnanceByConsultationDesc();
            }
        }
        return $this->findAll();
    }

//    /**
//     * @return Ordonnance[] Returns an array of Ordonnance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ordonnance
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
