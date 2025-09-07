<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findByTag(?int $tagId): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC');

        if ($tagId) {
            $qb->innerJoin('p.tags', 't')
                ->andWhere('t.id = :tagId')
                ->setParameter('tagId', $tagId);
        }

        return $qb;
    }
}
