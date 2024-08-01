<?php

namespace App\Tests\Functional\Controller\Cart\ShowCartController;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class ShowCartControllerFixture extends AbstractFixture
{
    public const ID_KEY = 'id';
    public const PRODUCT_NAME_KEY = 'productName';
    public const PRODUCT_PRICE_KEY = 'price';
    public const PRODUCTS_DATA = [
        [
            self::ID_KEY => '08ec8824-47b3-48a6-93e8-079863eb9f2c',
            self::PRODUCT_NAME_KEY=> 'Product 1',
            self::PRODUCT_PRICE_KEY =>1990
        ],
        [
            self::ID_KEY => '330bbf89-0200-42ff-a7c7-cdaeace8977f',
            self::PRODUCT_NAME_KEY => 'Product 2',
            self::PRODUCT_PRICE_KEY => 3990
        ],
        [
            self::ID_KEY => '8feb5f26-4648-41b1-a7f6-ed7b66d2d215',
            self::PRODUCT_NAME_KEY => 'Product 3',
            self::PRODUCT_PRICE_KEY =>4990
        ]
    ];

    public const SHOW_CART_ID = 'fab8f7c3-a641-43c1-92d3-ee871a55fa8a';

    public function load(ObjectManager $manager): void
    {
        $products = \iterator_to_array($this->createProducts());
        foreach ($products as $product) {
            $manager->persist($product);
        }

        $cart = new Cart(self::SHOW_CART_ID);
        $manager->persist($cart);

        $cartProducts = [
            new CartProduct('15e4a636-ef98-445b-86df-46e1cc0e10b5', $cart, $products[2], $products[2]->getPrice()),
            new CartProduct('9670ea5b-d940-4593-a2ac-4589be784203', $cart, $products[1], $products[1]->getPrice()),
            new CartProduct('fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', $cart, $products[0], $products[0]->getPrice()),
        ];
        foreach ($cartProducts as $cartProduct) {
            $cart->addProduct($cartProduct);
            $manager->persist($cartProduct);
        }

        $manager->flush();
    }

    private function createProducts(): \Generator
    {
        foreach (self::PRODUCTS_DATA as $productData) {
            yield new Product(
                $productData[self::ID_KEY],
                $productData[self::PRODUCT_NAME_KEY],
                $productData[self::PRODUCT_PRICE_KEY],
            );
        }
    }
}