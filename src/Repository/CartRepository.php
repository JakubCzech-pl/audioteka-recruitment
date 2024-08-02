<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Service\Cart\CartInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function getById(string $cartId): ?CartInterface
    {
        try {
            return $this->createQueryBuilder('c')
                ->where('c.id = :cartId')
                ->setParameter('cartId', $cartId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            return null;
        }
    }

    /**
     * @return CartInterface[]
     */
    public function getAll(): array
    {
        return $this->findAll();
    }

    public function save(CartInterface $cart): void
    {
        $this->_em->persist($cart);
        $this->_em->flush();
    }

    public function delete(CartInterface $cart): void
    {
        $this->_em->remove($cart);
        $this->_em->flush();
    }

    public function deleteCartsOverTheLimit(): void
    {
        $carts = $this->createQueryBuilder('c')
            ->addSelect('cp')
            ->join('c.cartProducts', 'cp')
            ->getQuery()
            ->getResult();

        foreach ($carts as $cart) {
            if ($cart->getTotalProductsQuantity() <= Cart::CAPACITY) {
                continue;
            }

            $this->_em->remove($cart);
        }

        $this->_em->flush();
    }
}
