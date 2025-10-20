<?php

namespace App\Repository;

use App\Entity\CompareItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompareItem>
 */
class CompareItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompareItem::class);
    }

    public function save(CompareItem $compareItem, bool $flush = false): void
    {
        $this->getEntityManager()->persist($compareItem);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CompareItem $compareItem, bool $flush = false): void
    {
        $this->getEntityManager()->remove($compareItem);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}