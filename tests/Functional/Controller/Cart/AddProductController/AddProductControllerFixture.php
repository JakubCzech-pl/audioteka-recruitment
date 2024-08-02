<?php

namespace App\Tests\Functional\Controller\Cart\AddProductController;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class AddProductControllerFixture extends AbstractFixture
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
    public const CART_ID = '5bd88887-7017-4c08-83de-8b5d9abde58c';
    public const FULL_CART_ID = '1e82de36-23f3-4ae7-ad5d-616295f1d6c0';

    public function load(ObjectManager $manager): void
    {
        $products = \iterator_to_array($this->createProducts());
        foreach ($products as $product) {
            $manager->persist($product);
        }

        $cart = new Cart(self::CART_ID);
        $manager->persist($cart);

        $fullCart = new Cart(self::FULL_CART_ID);
        $manager->persist($fullCart);

        $cartProducts = [
            new CartProduct('9670ea5b-d940-4593-a2ac-4589be784203', $fullCart, $products[1], $products[1]->getPrice()),
            new CartProduct('15e4a636-ef98-445b-86df-46e1cc0e10b5', $fullCart, $products[2], $products[2]->getPrice()),
            new CartProduct('00e91390-3af8-4735-bd06-0311e7131757', $fullCart, $products[3], $products[3]->getPrice()),
        ];
        foreach ($cartProducts as $cartProduct) {
            $fullCart->addProduct($cartProduct);
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