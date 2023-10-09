<?php
namespace App\Repository;

use App\Entity\Article;
use App\Entity\Categorie;
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
 /**
    * @return Article[] Returns an array of Article objects
     */
   public function findByCategorie(Categorie $categorie): array
    {
        return $this->createQueryBuilder('a')
        ->where('a.categorie_id', ':val')
        ->setParameter('val', $categorie)
        ->orderBy('a.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }

    public function findArticleByCategorie($elt): array
    {
        return $this->createQueryBuilder('a')
        ->where('a.titre like :val')
        ->orWhere('a.contenu like :val')
        ->setParameter('val',  '%'.$elt.'%')
        ->getQuery()
        ->getResult()
        ;
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

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
