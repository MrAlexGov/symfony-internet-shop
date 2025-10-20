<?php

namespace App\Repository;

use App\Entity\Compare;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Compare>
 */
class CompareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compare::class);
    }

    public function save(Compare $compare, bool $flush = false): void
    {
        $this->getEntityManager()->persist($compare);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Compare $compare, bool $flush = false): void
    {
        $this->getEntityManager()->remove($compare);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}