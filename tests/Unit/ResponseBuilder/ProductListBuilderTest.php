<?php

namespace App\Tests\Unit\ResponseBuilder;

use App\Entity\Product;
use App\ResponseBuilder\ProductListBuilder;
use App\Service\Catalog\ProductList;
use App\Service\Catalog\ProductListProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @covers \App\ResponseBuilder\ProductListBuilder
 */
class ProductListBuilderTest extends TestCase
{
    private UrlGeneratorInterface $urlGenerator;

    public function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->urlGenerator->method('generate')->willReturnCallback(
            fn(string $name, array $parameters): string => $name.json_encode($parameters, JSON_THROW_ON_ERROR)
        );
    }

    public function test_builds_empty_product_list(): void
    {
        $productListProviderMock = $this->createMock(ProductListProviderInterface::class);
        $productListProviderMock->method('getList')->willReturn(ProductList::createEmpty());
        $productListProviderMock->method('getTotalCount')->willReturn(0);

        $builder = new ProductListBuilder($productListProviderMock, $this->urlGenerator);

        self::assertEquals([
            'previous_page' => null,
            'next_page' => null,
            'count' => 0,
            'products' => [],
        ], $builder->__invoke('product-list', 0));
    }

    public function test_builds_first_page(): void
    {
        $products = [
            new Product('25cc9f5d-7702-4cb0-b6fc-f93b049055ca', 'Product 1', 1200),
            new Product('30e4e028-3b38-4cb9-9267-a9e515983337', 'Product 2', 1400),
            new Product('f6635017-982f-4544-9ac5-3d57107c0f0d', 'Product 3', 1500),
            new Product('b7747f7b-ae35-4225-af9a-6ecc803ebf0f', 'Product 4', 1600),
            new Product('e41ac303-11ab-446e-a253-28572278fdbe', 'Product 5', 1700)
        ];

        $productListProviderMock = $this->createMock(ProductListProviderInterface::class);
        $productListProviderMock->method('getList')->willReturn(ProductList::create($products[4], $products[3], $products[2]));
        $productListProviderMock->method('getTotalCount')->willReturn(5);

        $builder = new ProductListBuilder($productListProviderMock, $this->urlGenerator);

        self::assertEquals([
            'previous_page' => null,
            'next_page' => 'product-list{"page":1}',
            'count' => 5,
            'products' => [
                ['id' => 'e41ac303-11ab-446e-a253-28572278fdbe', 'name' => 'Product 5', 'price' => 1700],
                ['id' => 'b7747f7b-ae35-4225-af9a-6ecc803ebf0f', 'name' => 'Product 4', 'price' => 1600],
                ['id' => 'f6635017-982f-4544-9ac5-3d57107c0f0d', 'name' => 'Product 3', 'price' => 1500],
            ],
        ], $builder->__invoke('product-list', 0));
    }

    public function test_builds_last_page(): void
    {
        $products = [
            new Product('25cc9f5d-7702-4cb0-b6fc-f93b049055ca', 'Product 1', 1200),
            new Product('30e4e028-3b38-4cb9-9267-a9e515983337', 'Product 2', 1400),
            new Product('f6635017-982f-4544-9ac5-3d57107c0f0d', 'Product 3', 1500),
            new Product('b7747f7b-ae35-4225-af9a-6ecc803ebf0f', 'Product 4', 1600),
            new Product('e41ac303-11ab-446e-a253-28572278fdbe', 'Product 5', 1700)
        ];

        $productListProviderMock = $this->createMock(ProductListProviderInterface::class);
        $productListProviderMock->method('getList')->with()->willReturn(ProductList::create($products[1], $products[0]));
        $productListProviderMock->method('getTotalCount')->willReturn(5);

        $builder = new ProductListBuilder($productListProviderMock, $this->urlGenerator);

        $this->assertEquals([
            'previous_page' => 'product-list{"page":0}',
            'next_page' => null,
            'count' => 5,
            'products' => [
                ['id' => '30e4e028-3b38-4cb9-9267-a9e515983337', 'name' => 'Product 2', 'price' => 1400],
                ['id' => '25cc9f5d-7702-4cb0-b6fc-f93b049055ca', 'name' => 'Product 1', 'price' => 1200]
            ],
        ], $builder->__invoke('product-list', 1));
    }

    public function test_builds_middle_page(): void
    {
        $products = [
            new Product('25cc9f5d-7702-4cb0-b6fc-f93b049055ca', 'Product 1', 1200),
            new Product('30e4e028-3b38-4cb9-9267-a9e515983337', 'Product 2', 1400),
            new Product('f6635017-982f-4544-9ac5-3d57107c0f0d', 'Product 3', 1500),
            new Product('b7747f7b-ae35-4225-af9a-6ecc803ebf0f', 'Product 4', 1600),
            new Product('e41ac303-11ab-446e-a253-28572278fdbe', 'Product 5', 1700),
            new Product('0a5d83f1-8c7e-4253-b020-156439f3d3c9', 'Product 6', 1800),
            new Product('15e4a636-ef98-445b-86df-46e1cc0e10b5', 'Product 7', 1900),
        ];

        $productListProviderMock = $this->createMock(ProductListProviderInterface::class);
        $productListProviderMock->method('getList')->willReturn(ProductList::create($products[3], $products[2], $products[1]));
        $productListProviderMock->method('getTotalCount')->willReturn(7);

        $builder = new ProductListBuilder($productListProviderMock, $this->urlGenerator);

        self::assertEquals([
            'previous_page' => 'product-list{"page":0}',
            'next_page' => 'product-list{"page":2}',
            'count' => 7,
            'products' => [
                ['id' => 'b7747f7b-ae35-4225-af9a-6ecc803ebf0f', 'name' => 'Product 4', 'price' => 1600],
                ['id' => 'f6635017-982f-4544-9ac5-3d57107c0f0d', 'name' => 'Product 3', 'price' => 1500],
                ['id' => '30e4e028-3b38-4cb9-9267-a9e515983337', 'name' => 'Product 2', 'price' => 1400],
            ],
        ], $builder->__invoke('product-list', 1));
    }
}