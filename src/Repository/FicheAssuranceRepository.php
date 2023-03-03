<?php

namespace App\Repository;

use App\Entity\FicheAssurance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FicheAssurance>
 *
 * @method FicheAssurance|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheAssurance|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheAssurance[]    findAll()
 * @method FicheAssurance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheAssuranceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheAssurance::class);
    }

    public function save(FicheAssurance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FicheAssurance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function total_ficheAssurance(): float
    {
        $query = $this->createQueryBuilder('o')
            ->select("count(o) as total_ordonnance")
            ->andWhere('o.matricule_cnam = :matricule_cnam')
            ->setParameter('matricule_cnam', 'AZ2223')
            ->getQuery()
            ->getSingleResult();
    
        return $query['total_ordonnance'];
    }

    public function total_ficheAssuranceé(): float
    {
        $query = $this->createQueryBuilder('o')
            ->select("count(o) as total_ordonnance")
            ->andWhere('o.matricule_cnam = :matricule_cnam')
            ->setParameter('matricule_cnam', '3213')
            ->getQuery()
            ->getSingleResult();
    
        return $query['total_ordonnance'];
    }
//    /**
//     * @return FicheAssurance[] Returns an array of FicheAssurance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FicheAssurance
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
