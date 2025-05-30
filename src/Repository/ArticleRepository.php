<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
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
    public function searchByTitle(string $query): array
    {
        return $this->createQueryBuilder('a') // Définition du query builder
            ->where('a.title LIKE :val OR a.slug LIKE :val') // Où on cherche le titre ou le slug
            ->andWhere('a.is_published = true') // Les articles publiés
            ->setParameter('val', '%' .  strtolower($query) . '%') // Paramétrage de la valeur (value binding)
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
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
