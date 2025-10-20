<?php

namespace App\Repository;

use App\Entity\Wishlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wishlist>
 */
class WishlistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wishlist::class);
    }

    public function save(Wishlist $wishlist, bool $flush = false): void
    {
        $this->getEntityManager()->persist($wishlist);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Wishlist $wishlist, bool $flush = false): void
    {
        $this->getEntityManager()->remove($wishlist);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}