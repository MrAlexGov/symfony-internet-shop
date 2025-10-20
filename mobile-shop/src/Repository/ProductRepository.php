<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findWithFilters(
        ?string $search = null,
        ?int $categoryId = null,
        ?int $brandId = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        string $sort = 'name',
        string $order = 'ASC'
    ): array {
        $qb = $this->createQueryBuilder('p')
            ->where('p.isActive = :isActive')
            ->setParameter('isActive', true);

        // Поиск по названию и описанию
        if ($search) {
            $qb->andWhere('(p.name LIKE :search OR p.description LIKE :search OR p.sku LIKE :search)')
                ->setParameter('search', '%' . $search . '%');
        }

        // Фильтр по категории
        if ($categoryId) {
            $qb->andWhere('p.category = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        // Фильтр по бренду
        if ($brandId) {
            $qb->andWhere('p.brand = :brandId')
                ->setParameter('brandId', $brandId);
        }

        // Фильтр по цене
        if ($minPrice !== null) {
            $qb->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        // Связываем с брендом и категорией для получения полной информации
        $qb->leftJoin('p.brand', 'b')
            ->leftJoin('p.category', 'c')
            ->addSelect('b', 'c');

        // Сортировка
        $allowedSortFields = ['name', 'price', 'createdAt', 'sku'];
        $allowedOrders = ['ASC', 'DESC'];

        if (in_array($sort, $allowedSortFields) && in_array(strtoupper($order), $allowedOrders)) {
            $qb->orderBy('p.' . $sort, $order);
        } else {
            $qb->orderBy('p.name', 'ASC');
        }

        return $qb->getQuery()->getResult();
    }

    public function findFeatured(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isActive = :isActive')
            ->andWhere('p.isFeatured = :isFeatured')
            ->setParameter('isActive', true)
            ->setParameter('isFeatured', true)
            ->leftJoin('p.brand', 'b')
            ->leftJoin('p.category', 'c')
            ->addSelect('b', 'c')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();
    }
}
