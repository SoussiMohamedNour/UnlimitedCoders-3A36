<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public  function  trie3()
    {
        return $this->createQueryBuilder('article')
            ->setMaxResults(3)
            ->orderBy('article.article_date','DESC')
            ->getQuery()
            ->getResult();
    }
    public  function  trienblike()
    {
        return $this->createQueryBuilder('article')
            ->setMaxResults(3)
            ->orderBy('article.nblike','DESC')
            ->where('article.nblike > 0')
            ->getQuery()
            ->getResult();
    }
    public function countByDate(){
        $query = $this->createQueryBuilder('articles');
        $query
            ->select('SUBSTRING(articles.article_date , 1 , 10) as datearticle , COUNT(articles) as count')
            ->groupBy('datearticle')
        ;
        return $query->getQuery()->getResult();
    }
//    /**
//     * @return Article[] Returns an array of Article objects
//     */

    public function findrecByarticleTitle($title){
        return $this->createQueryBuilder('article')
            ->where('article.titre LIKE :titre')
            ->setParameter('titre', '%'.$title.'%')
            ->getQuery()
            ->getResult();
    }
    public function findbyNbcomment(){
        $query = $this->createQueryBuilder('article');
        $query
            ->select('article.nbcomment as nbcomment,article.titre as titre,article.id as id')
            ->groupBy('titre');
        return $query->getQuery()->getResult();
    }



//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
