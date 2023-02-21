<?php

namespace App\Repository;

use App\Entity\Medicament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medicament>
 *
 * @method Medicament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medicament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medicament[]    findAll()
 * @method Medicament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medicament::class);
    }

    public function save(Medicament $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Medicament $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Tri id
    public function getMedicamentByIdAsc():array{
        return $this->createQueryBuilder('m')
        ->orderBy('m.id','ASC')
        ->getQuery()
        ->getResult();
    }
    public function getMedicamentByIdDesc():array{
        return $this->createQueryBuilder('m')
        ->orderBy('m.id','DESC')
        ->getQuery()
        ->getResult();
    }

    // Tri Dosage
    public function getMedicamentByDosageAsc():array{
        return $this->createQueryBuilder('m')
        ->orderBy('m.dosage','ASC')
        ->getQuery()
        ->getResult();
    }
    public function getMedicamentByDosageDesc():array{
        return $this->createQueryBuilder('m')
        ->orderBy('m.dosage','DESC')
        ->getQuery()
        ->getResult();
    }

    // Tri Prix
    public function getMedicamentByPrixAsc():array{
        return $this->createQueryBuilder('m')
        ->orderBy('m.prix','ASC')
        ->getQuery()
        ->getResult();
    }
    public function getMedicamentByPrixDesc():array{
        return $this->createQueryBuilder('m')
        ->orderBy('m.prix','DESC')
        ->getQuery()
        ->getResult();
    }
    public function trier(string $critere,string $ordre):array{
        if($critere == "id")
        {
            if($ordre == "asc")
            {
                return $this->getMedicamentByIdAsc();
            }
            else if ($ordre == "desc" )
            {
                return $this->getMedicamentByIdDesc();
            }

        }
        else if($critere == "dosage")
        {
            if($ordre == "asc")
            {
                return $this->getMedicamentByDosageAsc();
            }
            else if ($ordre == "desc")
            {
                return $this->getMedicamentByDosageDesc();
            }
        }
        else if($critere == "prix")
        {
            if($ordre == "asc")
            {
                return $this->getMedicamentByPrixAsc();
            }
            else if($ordre == "desc")
            {
                return $this->getMedicamentByPrixDesc();
            }
        }
        return $this->findAll();
    }

//    /**
//     * @return Medicament[] Returns an array of Medicament objects
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

//    public function findOneBySomeField($value): ?Medicament
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
