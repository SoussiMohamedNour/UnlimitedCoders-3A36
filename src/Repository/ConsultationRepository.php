<?php

namespace App\Repository;

use App\Entity\Consultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Consultation>
 *
 * @method Consultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consultation[]    findAll()
 * @method Consultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consultation::class);
    }

    public function save(Consultation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Consultation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    

    // Tri Date Consultation
    public function getConsultationByDateAsc():array{
        return $this->createQueryBuilder('c')
        ->orderBy('c.dateconsultation','ASC')
        ->getQuery()
        ->getResult();
    }
    public function getConsultationByDateDsc():array{
        return $this->createQueryBuilder('c')
        ->orderBy('c.dateconsultation','DESC')
        ->getQuery()
        ->getResult();
    }

    // Tri Matricule
    public function getConsultationbyMatriculeAsc():array{
        return $this->createQueryBuilder('c')
        ->orderBy('c.matriculemedecin','ASC')
        ->getQuery()
        ->getResult();
    }
    public function getConsultationbyMatriculeDesc():array{
        return $this->createQueryBuilder('c')
        ->orderBy('c.matriculemedecin','DESC')
        ->getQuery()
        ->getResult();
    }

    // Tri Montant
    public function getConsultationByMontantAsc():array{
        return $this->createQueryBuilder('c')
        ->orderBy('c.montant','ASC')
        ->getQuery()
        ->getResult();
    }
    public function getConsultationByMontantDesc():array{
        return $this->createQueryBuilder('c')
        ->orderBy('c.montant','DESC')
        ->getQuery()
        ->getResult();
    }
    public function returnAll():array
    {
        return $this->findAll();
    }

    public function trier(string $critere,string $ordre):array
    {
        if($critere == "montant")
        {
            if($ordre == "asc" )
            {
                return $this->getConsultationByMontantAsc();
            }
            else if($ordre == "desc")
            {
                return $this->getConsultationByMontantDesc();
            }
        }
        else if($critere == "matricule")
        {
            if($ordre == "asc" )
            {
                return $this->getConsultationbyMatriculeAsc();
            }
            else if($ordre == "desc")
            {
                return $this->getConsultationbyMatriculeDesc();
            }
        }
        else if($critere == "date")
        {
            if($ordre == "asc" )
            {
                return $this->getConsultationByDateAsc();
            }
            else if($ordre == "desc")
            {
                return $this->getConsultationByDateDsc();
            }
        }
        return $this->findAll();

    }

//    /**
//     * @return Consultation[] Returns an array of Consultation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Consultation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
