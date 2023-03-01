<?php

namespace App\Repository;

use App\Entity\Depot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Depot>
 *
 * @method Depot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depot[]    findAll()
 * @method Depot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depot::class);
    }

    public function save(Depot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Depot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Depot[] Returns an array of Depot objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Depot
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    //get assurance by patient from depot
    public function getAssuranceByPatient($idPatient): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT d.assurance
            FROM App\Entity\Depot d
            WHERE d.patient = :idPatient'
        )->setParameter('idPatient', $idPatient);

        // returns an array of Product objects
        return $query->getResult();
    }

    //get all by fiche from entity Fiche
    public function getAllByFiche($idFiche): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT d
            FROM App\Entity\Fiche d
            WHERE d.id = :idFiche'
        )->setParameter('idFiche', $idFiche);

        // returns an array of Product objects
        return $query->getResult();
    }

    public function countApprovedDepot(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(d.id)
            FROM App\Entity\Depot d
            WHERE d.etatDossier = :etatDossier'
        )->setParameter('etatDossier', 'approuve');

        return $query->getResult();
    }

        //count refus depot
        public function countRefusDepot(): array
        {
            $entityManager = $this->getEntityManager();
    
            $query = $entityManager->createQuery(
                'SELECT COUNT(d.id)
                FROM App\Entity\Depot d
                WHERE d.etatDossier = :etatDossier'
            )->setParameter('etatDossier', 'rejete');
    
            return $query->getResult();
        }
        //count en attent depot
        public function countEnAttentDepot(): array
        {
            $entityManager = $this->getEntityManager();
    
            $query = $entityManager->createQuery(
                'SELECT COUNT(d.id)
                FROM App\Entity\Depot d
                WHERE d.etatDossier = :etatDossier'
            )->setParameter('etatDossier', 'en_attente');
    
            return $query->getResult();
        }

   //search multiple

    public function searchM($search,$id)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT d
            FROM App\Entity\Depot d
            WHERE d.idDossier LIKE :search
            OR d.regime LIKE :search
            OR d.etatDossier LIKE :search
            OR d.dateDepot LIKE :search
            OR d.totalDepense LIKE :search
            AND d.patient = :id
            '
        )->setParameter('search', '%'.$search.'%')
        ->setParameter('id', $id);
        
        ;

             return $query->getResult();

     

    }

    //getAll depot by idDepot
    public function getAllDepotById($idDepot): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT d
            FROM App\Entity\Depot d
            WHERE d.idDossier = :idDepot'
        )->setParameter('idDepot', $idDepot);

        // returns an array of Product objects
        return $query->getResult();
    }

}
