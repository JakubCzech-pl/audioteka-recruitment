<?php

namespace App\Service\Cart;

use App\Service\Catalog\Product;

interface CartServiceInterface
{
    public function addProduct(string $cartId, string $productId, int $quantity): void;

    public function removeProduct(string $cartId, string $cartProductId): void;

    public function create(): CartInterface;
}