<?php

declare(strict_types=1);

namespace App\Tests\Unit\ResponseBuilder;

use App\Entity\Product;
use App\ResponseBuilder\ProductViewBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\ResponseBuilder\ProductViewBuilder
 */
class ProductViewBuilderTest extends TestCase
{
    private ProductViewBuilder $productViewBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->productViewBuilder = new ProductViewBuilder();
    }

    public function test_build_product_view(): void
    {
        $product = new Product('25cc9f5d-7702-4cb0-b6fc-f93b049055ca', 'Product 1', 1200);

        self::assertEquals(
            [
                'id' => '25cc9f5d-7702-4cb0-b6fc-f93b049055ca',
                'name' => 'Product 1',
                'price' => 1200
            ],
            $this->productViewBuilder->__invoke($product)
        );
    }
}
