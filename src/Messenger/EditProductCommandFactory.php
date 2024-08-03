<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Entity\Product;
use App\Exception\EditProduct\NoNewProductValuesException;

class EditProductCommandFactory
{
    /**
     * @throws NoNewProductValuesException
     */
    public function create(Product $product, ?string $name, ?int $price): EditProduct
    {
        if (!$this->hasValueToChange($product, $name, $price)) {
            throw new NoNewProductValuesException();
        }

        return new EditProduct(
            $product->getId(),
            \is_null($name) ? $product->getName() : $name,
            \is_null($price) ? $product->getPrice() : $price
        );
    }

    private function hasValueToChange(Product $product, ?string $name, ?int $price): bool
    {
        if (!$name && !$price) {
            return false;
        }

        return $product->getName() !== $name || $product->getPrice() !== $price;
    }
}
