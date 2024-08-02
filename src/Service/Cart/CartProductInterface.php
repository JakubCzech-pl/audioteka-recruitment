<?php

declare(strict_types=1);

namespace App\Service\Cart;

use App\Entity\Product;

interface CartProductInterface
{
    public function getId(): string;
    public function getCart(): CartInterface;
    public function getProduct(): Product;
    public function getProductName(): string;
    public function getPrice(): int;
    public function getQuantity(): int;
}
