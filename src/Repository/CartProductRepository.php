<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CartProduct;
use App\Service\Cart\CartProductInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class CartProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProduct::class);
    }

    public function getById(string $cartProductId): ?CartProductInterface
    {
        try {
            return $this->createQueryBuilder('cp')
                ->where('cp.id = :id')
                ->setParameter('id', $cartProductId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            return null;
        }
    }

    /**
     * @return CartProductInterface[]
     */
    public function getByCartId(string $cartId): array
    {
        return $this->createQueryBuilder('cp')
            ->where('cp.cart_id = :cartId')
            ->setParameter('cartId', $cartId)
            ->getQuery()
            ->getResult();
    }

    public function save(CartProductInterface $cartProduct): void
    {
        $this->_em->persist($cartProduct);
        $this->_em->flush();
    }

    public function delete(CartProduct $cartProduct): void
    {
        $this->_em->remove($cartProduct);
        $this->_em->flush();
    }
}