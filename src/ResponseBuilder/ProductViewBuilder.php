<?php

declare(strict_types=1);

namespace App\ResponseBuilder;

use App\Entity\Product;

class ProductViewBuilder
{
    public function __invoke(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice()
        ];
    }
}
