<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CartProductRepository;
use App\Service\Cart\CartInterface;
use App\Service\Cart\CartProductInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Nonstandard\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: CartProductRepository::class)]
#[ORM\Table(name: 'cart_products')]
class CartProduct implements CartProductInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: 'Cart', cascade: ['persist'], inversedBy: 'cartProduct')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private CartInterface $cart;

    #[ORM\ManyToOne(targetEntity: 'Product', cascade: ['persist'], inversedBy: 'cartProduct')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Product $product;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $price;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $quantity;

    public function __construct(
        string $id,
        CartInterface $cart,
        Product $product,
        int $price,
        int $quantity = 1
    ) {
        $this->id = Uuid::fromString($id);
        $this->cart = $cart;
        $this->product = $product;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getCart(): CartInterface
    {
        return $this->cart;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getProductName(): string
    {
        return $this->getProduct()->getName();
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
