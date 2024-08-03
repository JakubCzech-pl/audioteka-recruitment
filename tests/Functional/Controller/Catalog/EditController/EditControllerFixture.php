<?php

namespace App\Tests\Functional\Controller\Catalog\EditController;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class EditControllerFixture extends AbstractFixture
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
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PRODUCTS_DATA as $productData) {
            $product = new Product(
                $productData['id'],
                $productData['name'],
                $productData['price'],
            );
             $manager->persist($product);
        }

        $manager->flush();
    }
}