<?php

namespace App\Service\Cart;

interface CartInterface
{
    public function getId(): string;
    public function getTotalPrice(): int;
    public function isFull(): bool;
    /**
     * @return CartProductInterface[]
     */
    public function getCartProducts(): iterable;
    public function addProduct(CartProductInterface $cartProduct): void;
    public function removeProduct(CartProductInterface $product): void;
}
