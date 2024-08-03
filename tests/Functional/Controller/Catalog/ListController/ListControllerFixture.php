<?php

namespace App\Tests\Functional\Controller\Catalog\ListController;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class ListControllerFixture extends AbstractFixture
{
    private const PRODUCTS_DATA = [
        [
            'id' => 'fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
            'name' => 'Product 1',
            'price' => 1990
        ],
        [
            'id' => '9670ea5b-d940-4593-a2ac-4589be784203',
            'name' => 'Product 2',
            'price' => 3990
        ],
        [
            'id' => '15e4a636-ef98-445b-86df-46e1cc0e10b5',
            'name' => 'Product 3',
            'price' => 4990
        ],
        [
            'id' => '00e91390-3af8-4735-bd06-0311e7131757',
            'name' => 'Product 4',
            'price' => 5990
        ],
        [
            'id' => '0a5d83f1-8c7e-4253-b020-156439f3d3c9',
            'name' => 'Product 5',
            'price' => 6990
        ],
        [
            'id' => 'e41ac303-11ab-446e-a253-28572278fdbe',
            'name' => 'Product 6',
            'price' => 7990
        ],
        [
            'id' => 'b7747f7b-ae35-4225-af9a-6ecc803ebf0f',
            'name' => 'Product 7',
            'price' => 8990
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $i = 0;
        $products = [];
        foreach (self::PRODUCTS_DATA as $productData) {
            $dateTime = new \DateTime();
            $dateTime->modify('+ ' . $i++ . ' day');
            $products[] = new Product(
                $productData['id'],
                $productData['name'],
                $productData['price'],
                $dateTime
            );
        }

        foreach ($products as $product) {
            $manager->persist($product);
        }

        $manager->flush();
    }
}