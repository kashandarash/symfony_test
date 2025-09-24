<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Article::class);
  }

  /**
   * Get Articles count.
   *
   * @param array $parameters
   * @param ?string $search_string
   *
   * @return int
   */
  public function countArticles(array $parameters, ?string $search_string = NULL): int {
    $qb = $this->createQueryBuilder('a')->select('COUNT(a.id)');

    foreach ($parameters as $key => $value) {
      $qb->andWhere("a.$key = :$key")->setParameter($key, $value);
    }

    // Apply search on title OR content
    if (!empty($search_string)) {
      $qb->andWhere(
        $qb->expr()->orX(
          $qb->expr()->like('a.title', ':search'),
          $qb->expr()->like('a.content', ':search')
        )
      )->setParameter('search', '%' . $search_string . '%');
    }

    return (int) $qb->getQuery()->getSingleScalarResult();
  }

  /**
   * Find articles.
   *
   * @param array $parameters
   * @param string $order
   * @param string|null $search_string
   * @param int|null $limit
   * @param int|null $offset
   *
   * @return array
   */
  public function findArticles(array $parameters = [], string $order = 'DESC', ?string $search_string = NULL, ?int $limit = NULL, ?int $offset = NULL): array {
    $qb = $this->createQueryBuilder('a')->orderBy('a.id', $order);

    foreach ($parameters as $key => $value) {
      $qb->andWhere("a.$key = :$key")->setParameter($key, $value);
    }

    // Apply search on title OR content
    if (!empty($search_string)) {
      $qb->andWhere(
        $qb->expr()->orX(
          $qb->expr()->like('a.title', ':search'),
          $qb->expr()->like('a.content', ':search')
        )
      )->setParameter('search', '%' . $search_string . '%');
    }

    // Apply limit/offset
    if ($limit !== NULL) {
      $qb->setMaxResults($limit);
    }
    if ($offset !== NULL) {
      $qb->setFirstResult($offset);
    }

    return $qb->getQuery()->getResult();
  }

}
