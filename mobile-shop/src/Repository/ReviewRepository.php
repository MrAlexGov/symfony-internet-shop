<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findApprovedByProduct($productId): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.product = :productId')
            ->andWhere('r.isApproved = :isApproved')
            ->andWhere('r.isActive = :isActive')
            ->setParameter('productId', $productId)
            ->setParameter('isApproved', true)
            ->setParameter('isActive', true)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getAverageRating($productId): float
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.rating) as avg_rating')
            ->where('r.product = :productId')
            ->andWhere('r.isApproved = :isApproved')
            ->andWhere('r.isActive = :isActive')
            ->setParameter('productId', $productId)
            ->setParameter('isApproved', true)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float) $result : 0.0;
    }
}