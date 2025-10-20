<?php

namespace App\Repository;

use App\Entity\WishlistItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WishlistItem>
 */
class WishlistItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishlistItem::class);
    }

    public function save(WishlistItem $wishlistItem, bool $flush = false): void
    {
        $this->getEntityManager()->persist($wishlistItem);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WishlistItem $wishlistItem, bool $flush = false): void
    {
        $this->getEntityManager()->remove($wishlistItem);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}