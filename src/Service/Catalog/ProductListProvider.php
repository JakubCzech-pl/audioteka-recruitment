<?php

declare(strict_types=1);

namespace App\Service\Catalog;

use App\Repository\ProductRepository;

class ProductListProvider implements ProductListProviderInterface
{
    public function __construct(private ProductRepository $productRepository) {}

    public function getList(int $page = 0): ProductListInterface
    {
        $products = $this->productRepository->getProductsOrderedByDate(
            self::MAX_PER_PAGE,
            $page * self::MAX_PER_PAGE
        );
        if (empty($products)) {
            return ProductList::createEmpty();
        }

        return ProductList::create(
            ...$products
        );
    }

    public function getTotalCount(): int
    {
        return $this->productRepository->countAll();
    }
}
