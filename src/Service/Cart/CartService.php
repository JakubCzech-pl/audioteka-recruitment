<?php

declare(strict_types=1);

namespace App\Service\Cart;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Ramsey\Uuid\Uuid;

class CartService implements CartServiceInterface
{
    public function __construct(
        private CartRepository $cartRepository,
        private CartProductRepository $cartProductRepository,
        private ProductRepository $productRepository
    ) {}

    public function create(): CartInterface
    {
        $cart = new Cart(Uuid::uuid4()->toString());
        $this->cartRepository->save($cart);

        return $cart;
    }

    public function addProduct(string $cartId, string $productId, int $quantity): void
    {
        $cart = $this->cartRepository->getById($cartId);
        if (!$cart) {
            return;
        }

        $product = $this->productRepository->getById($productId);
        if (!$product) {
            return;
        }

        $cartProduct = new CartProduct(
            Uuid::uuid4()->toString(),
            $cart,
            $product,
            $product->getPrice(),
            $quantity
        );

        $cart->addProduct($cartProduct);

        $this->cartProductRepository->save($cartProduct);
        $this->cartRepository->save($cart);
    }

    public function removeProduct(string $cartId, string $cartProductId): void
    {
        $cart = $this->cartRepository->getById($cartId);
        if (!$cart) {
            return;
        }

        $cartProduct = $this->cartProductRepository->getById($cartProductId);
        if (!$cartProduct) {
            return;
        }

        if (!$cart->hasProduct($cartProduct)) {
            return;
        }

        $cart->removeProduct($cartProduct);
        $this->cartProductRepository->delete($cartProduct);

        $this->cartRepository->save($cart);
    }
}
