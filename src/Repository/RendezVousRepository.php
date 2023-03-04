<?php

namespace App\Repository;

use App\Entity\RendezVous;
use ContainerGfFpkFx\getTranslation_Loader_JsonService;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Response;

/**
 * @extends ServiceEntityRepository<RendezVous>
 *
 * @method RendezVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVous[]    findAll()
 * @method RendezVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }

    public function save(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findRendezVousByUtilisateurId($utilisateurId)
    {
        return $this->createQueryBuilder('r')
            ->join('r.utilisateur', 'u')
            ->where('u.id = :utilisateurId')
            ->setParameter('utilisateurId', $utilisateurId)
            ->orderBy('r.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findRendezVousByPatientId($patientId)
    {
        return $this->createQueryBuilder('r')
            ->join('r.patient', 'u')
            ->where('u.id = :patientId')
            ->setParameter('patientId', $patientId)
            ->orderBy('r.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findRendezVousBetweenDates(DateTime $startDate, DateTime $endDate)
    {
        $qb = $this->createQueryBuilder('r');

        $qb->where('r.date >= :startDate')
            ->andWhere('r.date < :endDate')
            ->setParameter('startDate', $startDate->format('Y-m-d'))
            ->setParameter('endDate', $endDate->format('Y-m-d'));

        return $qb->getQuery()->getResult();
    }
    public function getNumberOfAppointmentsForDoctor($utilisateur, $startDate, $endDate)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('COUNT(r.id)');
        $qb->where('r.utilisateur = :utilisateurId')
            ->andWhere('r.date BETWEEN :startDate AND :endDate')
            ->setParameter('utilisateurId', $utilisateur->getId())
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result;
    }
    public function getNumberOfAppointmentsPerDay($utilisateur, $startDate, $endDate, ): array
    {

        $qb = $this->createQueryBuilder('r');
        $qb->select('COUNT(r.id) as numberOfAppointments,r.date as date');
        $qb->where('r.utilisateur = :utilisateur')
            ->andWhere('r.date BETWEEN :startDate AND :endDate')
            ->setParameter('utilisateur', $utilisateur->getId())
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('date');

        $query = $qb->getQuery();
        $results = $query->getResult();

        $data = [];

        foreach ($results as $result) {
            $data[] = [
                'numberOfAppointments' => $result['numberOfAppointments'],
                'date' => $result['date'],
            ];
        }
        return $data;
    }





//    /**
//     * @return RendezVous[] Returns an array of RendezVous objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RendezVous
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



}
