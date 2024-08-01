<?php

namespace App\Tests\Functional\Controller\Cart\RemoveProductController;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class RemoveProductControllerFixture extends AbstractFixture
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
        ],
        [
            self::ID_KEY => 'af743092-0874-4e64-a8cd-04309094bb94',
            self::PRODUCT_NAME_KEY => 'Product 4',
            self::PRODUCT_PRICE_KEY => 5990
        ],
    ];

    public const CART_ID = '97e385fe-9876-45fc-baa0-4f2f0df90950';
    public const CART_PRODUCT_ID_TO_DELETE = 'd11e1e69-cca7-40a1-8273-9d93c8346efd';

    public function load(ObjectManager $manager): void
    {
        $products = \iterator_to_array($this->createProducts());
        foreach ($products as $product) {
            $manager->persist($product);
        }

        $cart = new Cart(self::CART_ID);
        $manager->persist($cart);

        $cartProduct = new CartProduct(
            self::CART_PRODUCT_ID_TO_DELETE,
            $cart,
            $products[0],
            $products[0]->getPrice()
        );
        $manager->persist($cartProduct);
        $cart->addProduct($cartProduct);

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