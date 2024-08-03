<?php

namespace App\Tests\Functional\Controller\Catalog\EditController;

use App\Tests\Functional\WebTestCase;

class EditControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new EditControllerFixture());
    }

    public function test_edit_product_name(): void
    {
        $this->client->request(
            'PUT',
            '/products/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
            ['name' => 'Product 1 New']
        );

        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/product/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        $response = $this->getJsonResponse();
        self::assertEquals('Product 1 New', $response['name']);
    }

    public function test_edit_product_price(): void
    {
        $this->client->request(
            'PUT',
            '/products/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
            ['price' => 5555]
        );

        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/product/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        $response = $this->getJsonResponse();
        self::assertEquals(5555, $response['price']);
    }

    public function test_edit_product_name_and_price(): void
    {
        $this->client->request(
            'PUT',
            '/products/15e4a636-ef98-445b-86df-46e1cc0e10b5',
            ['price' => 9999, 'name' => 'Product 3 New']
        );

        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/product/15e4a636-ef98-445b-86df-46e1cc0e10b5');
        $response = $this->getJsonResponse();
        self::assertEquals(9999, $response['price']);
        self::assertEquals('Product 3 New', $response['name']);
    }

    public function test_no_parameters_product_edit(): void
    {
        $this->client->request(
            'PUT',
            '/products/9670ea5b-d940-4593-a2ac-4589be784203'
        );

        $response = $this->getJsonResponse();

        self::assertResponseStatusCodeSame(422);
        self::assertEquals('No changes detected.', $response['error_message']);
    }

    public function test_same_parameters_product_edit(): void
    {
        $this->client->request(
            'PUT',
            '/products/9670ea5b-d940-4593-a2ac-4589be784203',
            ['name' => 'Product 2', 'price' => 3990]
        );

        $response = $this->getJsonResponse();

        self::assertResponseStatusCodeSame(422);
        self::assertEquals('No changes detected.', $response['error_message']);
    }
}