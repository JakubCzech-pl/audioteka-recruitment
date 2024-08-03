<?php

declare(strict_types=1);

namespace App\Service\Catalog;

class ProductList implements ProductListInterface
{
    private const ID_KEY = 'id';
    private const NAME_KEY = 'name';
    private const PRICE_KEY = 'price';

    private const CREATED_AT_KEY = 'created_at';

    /**
     * @param ProductInterface[] $products
     */
    private function __construct(private array $products) {}

    public static function create(ProductInterface ...$products): self
    {
        return new self($products);
    }

    public static function createEmpty(): self
    {
        return new self([]);
    }

    public function getProducts(): \Generator
    {
        foreach ($this->products as $product) {
            yield [
                self::ID_KEY => $product->getId(),
                self::NAME_KEY => $product->getName(),
                self::PRICE_KEY => $product->getPrice(),
            ];
        }
    }
}
