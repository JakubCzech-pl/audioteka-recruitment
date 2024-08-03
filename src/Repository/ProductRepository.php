<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProductsOrderedByDate(int $limit, int $startsFrom = 0, string $order = 'DESC'): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', $order)
            ->setMaxResults($limit)
            ->setFirstResult($startsFrom)
            ->getQuery()
            ->getResult();
    }

    public function getById(string $productId): ?Product
    {
        try {
            return $this->createQueryBuilder('p')
                ->where('p.id = :productId')
                ->setParameter('productId', $productId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            return null;
        }
    }

    public function countAll(): int
    {
        return $this->count([]);
    }

    public function update(string $productId, string $name, int $price): void
    {
        $this->createQueryBuilder('p')
            ->update()
            ->set('p.name', ':name')
            ->set('p.priceAmount', ':priceAmount')
            ->where('p.id = :productId')
            ->setParameter('productId', $productId)
            ->setParameter('name', $name)
            ->setParameter('priceAmount', $price)
            ->getQuery()
            ->execute();
    }

    public function save(Product $product): void
    {
        $this->_em->persist($product);
        $this->_em->flush();
    }

    public function delete(Product $product): void
    {
        $this->_em->remove($product);
        $this->_em->flush();
    }
}
