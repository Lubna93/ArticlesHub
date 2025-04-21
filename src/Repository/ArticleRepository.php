<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

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


    public function findByNewestQueryBuilder(string $search = null): QueryBuilder
    {
        $queryBuilder = $this
            ->createQueryBuilder('Article')
            ->where('Article.published = TRUE')
            ->orderBy('Article.id', 'DESC')
        ;

        if ($search) {
            $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(Article.title)', ':searchTerm'),
                    $queryBuilder->expr()->like('LOWER(Article.body)', ':searchTerm'),
                )
            )
            ->setParameter('searchTerm', '%' . strtolower($search) . '%');
        }

        return $queryBuilder;

    }

    public function searchByName($searchTerm)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.title LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }



}
