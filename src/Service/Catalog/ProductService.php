<?php

declare(strict_types=1);

namespace App\Service\Catalog;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Ramsey\Uuid\Uuid;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepository $productRepository) {}

    public function add(string $name, int $price): void
    {
        $product = new Product(Uuid::uuid4()->toString(), $name, $price);

        $this->productRepository->save($product);
    }

    public function edit(string $productId, string $name, int $price): void
    {
        $this->productRepository->update($productId, $name, $price);
    }

    public function remove(string $productId): void
    {
        $productToDelete = $this->productRepository->getById($productId);
        if (!$productToDelete) {
            return;
        }

        $this->productRepository->delete($productToDelete);
    }
}
