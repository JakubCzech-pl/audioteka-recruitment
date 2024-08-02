<?php

namespace App\ResponseBuilder;

use App\Service\Cart\CartInterface;

class CartBuilder
{
    public function __invoke(CartInterface $cart): array
    {
        $data = [
            'total_price' => $cart->getTotalPrice(),
            'products' => []
        ];

        foreach ($cart->getCartProducts() as $cartProduct) {
            $data['products'][] = [
                'id' => $cartProduct->getId(),
                'name' => $cartProduct->getProductName(),
                'price' => $cartProduct->getPrice(),
                'quantity' => $cartProduct->getQuantity(),
            ];
        }

        return $data;
    }
}
