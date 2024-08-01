<?php

namespace App\Entity;

use App\Repository\CartRepository;
use App\Service\Cart\CartInterface;
use App\Service\Cart\CartProductInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart implements CartInterface
{
    public const CAPACITY = 3;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: 'CartProduct', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $cartProducts;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTotalPrice(): int
    {
        return array_reduce(
            $this->cartProducts->toArray(),
            static fn(int $total, CartProductInterface $product): int => $total + $product->getPrice(),
            0
        );
    }

    #[Pure]
    public function isFull(): bool
    {
        return $this->getTotalProductsQuantity() >= self::CAPACITY;
    }

    public function getCartProducts(): iterable
    {
        return $this->cartProducts->getIterator();
    }

    public function addProduct(CartProductInterface $cartProduct): void
    {
        $this->cartProducts->add($cartProduct);
    }

    public function removeProduct(CartProductInterface $product): void
    {
        $this->cartProducts->removeElement($product);
    }

    #[Pure]
    public function hasProduct(CartProductInterface $product): bool
    {
        return $this->cartProducts->contains($product);
    }

    public function getTotalProductsQuantity(): int
    {
        $count = 0;
        foreach ($this->cartProducts as $cartProduct) {
            $count += $cartProduct->getQuantity();
        }

        return $count;
    }
}
