<?php

namespace App\Repository;

use App\Entity\MedicamentTakwa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MedicamentTakwa>
 *
 * @method MedicamentTakwa|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicamentTakwa|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicamentTakwa[]    findAll()
 * @method MedicamentTakwa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicamentTakwaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicamentTakwa::class);
    }

    public function save(MedicamentTakwa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MedicamentTakwa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MedicamentTakwa[] Returns an array of MedicamentTakwa objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MedicamentTakwa
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
