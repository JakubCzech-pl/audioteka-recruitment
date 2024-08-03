<?php

namespace App\Repository;

use App\Entity\Product;
use App\Service\Catalog\ProductService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;

/**
 * @TODO Refactor the repository to contain only actions expected in repositories.
 * @TODO Separate it from ProductService
 */
class ProductRepository extends ServiceEntityRepository implements ProductService
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

    public function exists(string $productId): bool
    {
        return $this->find($productId) !== null;
    }

    public function add(string $name, int $price): Product
    {
        $product = new Product(Uuid::uuid4(), $name, $price);

        $this->_em->persist($product);
        $this->_em->flush();

        return $product;
    }

    public function remove(string $id): void
    {
        $product = $this->find($id);
        if ($product !== null) {
            $this->_em->remove($product);
            $this->_em->flush();
        }
    }
}
