<?php

namespace App\Repository;

use App\Entity\Facteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * @extends ServiceEntityRepository<Facteur>
 *
 * @method Facteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facteur[]    findAll()
 * @method Facteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facteur::class);
    }

    public function save(Facteur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Facteur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findProductsPaginated(int $page, int $limit = 6): array
    {
        $limit = abs($limit);

        $result = [];

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select( 'p')
            ->from('App\Entity\Facteur', 'p')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit) - $limit);


            $paginator = new Paginator($query);
            $data = $paginator->getQuery()->getResult();
            
           
            if(empty($data)){
                return $result;
            }
    
            //On calcule le nombre de pages
            $pages = ceil($paginator->count() / $limit);
    
            // On remplit le tableau
            $result['data'] = $data;
            $result['pages'] = $pages;
            $result['page'] = $page;
            $result['limit'] = $limit;
        return $result;
    }
//    /**
//     * @return Facteur[] Returns an array of Facteur objects
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

//    public function findOneBySomeField($value): ?Facteur
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
