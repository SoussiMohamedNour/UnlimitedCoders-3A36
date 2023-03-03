<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function save(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search($nom)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom LIKE :nom ')
            ->setParameter('nom', '%'.$nom.'%')
            ->getQuery()
            ->execute();
    }
    public function chart_repository(){
        return  $this->createQueryBuilder('r')

                    ->join('r.categorie','s')
                    -> addSelect('s')
                    ->where('r.categorie=s.id')
                    -> select('s.nom, COUNT(r.id) as count')

                   ->groupBy('s.nom')  
                   ->getQuery()
                   ->getResult()
               ;
       }
}
