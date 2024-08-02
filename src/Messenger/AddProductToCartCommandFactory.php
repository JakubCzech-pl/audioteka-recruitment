<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Entity\Cart;
use App\Entity\Product;
use App\Exception\AddProductToCart\CartIsFullException;
use App\Exception\AddProductToCart\QuantityOverTheLimitException;

class AddProductToCartCommandFactory
{
    /**
     * @throws CartIsFullException|QuantityOverTheLimitException
     */
    public function create(Cart $cart, Product $product, int $quantity): AddProductToCart
    {
        $this->validateQuantity($cart, $quantity);

        return new AddProductToCart(
            $cart->getId(),
            $product->getId(),
            $quantity
        );
    }

    /**
     * @throws CartIsFullException|QuantityOverTheLimitException
     */
    private function validateQuantity(Cart $cart, int $quantity): void
    {
        if ($cart->isFull()) {
            throw new CartIsFullException();
        }

        if ($this->willExceedCartLimit($cart, $quantity)) {
            throw new QuantityOverTheLimitException(Cart::CAPACITY - $cart->getTotalProductsQuantity());
        }
    }

    private function willExceedCartLimit(Cart $cart, int $quantity): bool
    {
        return $cart->getTotalProductsQuantity() + $quantity > Cart::CAPACITY;
    }
}
