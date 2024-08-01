<?php

namespace App\Service\Cart;

interface CartServiceInterface
{
    public function addProduct(string $cartId, string $productId, int $quantity): void;

    public function removeProduct(string $cartId, string $cartProductId): void;

    public function create(): CartInterface;
}
