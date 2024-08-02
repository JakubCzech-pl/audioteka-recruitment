<?php

namespace App\Tests\Functional\Controller\Cart\AddProductController;

use App\Tests\Functional\WebTestCase;

class AddProductControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new AddProductControllerFixture());
    }

    public function test_adds_product_to_cart(): void
    {
        $uri = '/cart/' .
            AddProductControllerFixture::CART_ID .
            '/' .
            AddProductControllerFixture::PRODUCTS_DATA[0][AddProductControllerFixture::ID_KEY];
        $this->client->request('PUT', $uri);
        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/cart/' . AddProductControllerFixture::CART_ID);
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();

        self::assertEquals(
            AddProductControllerFixture::PRODUCTS_DATA[0][AddProductControllerFixture::PRODUCT_NAME_KEY],
            $response['products'][0]['name']
        );
        self::assertEquals(
            AddProductControllerFixture::PRODUCTS_DATA[0][AddProductControllerFixture::PRODUCT_PRICE_KEY],
            $response['products'][0]['price']
        );
        self::assertEquals(
            1,
            $response['products'][0]['quantity']
        );

        self::assertCount(1, $response['products']);
    }

    public function test_refuses_to_add_fourth_product_to_cart(): void
    {
        $uri = '/cart/' .
            AddProductControllerFixture::FULL_CART_ID .
            '/' .
            AddProductControllerFixture::PRODUCTS_DATA[0][AddProductControllerFixture::ID_KEY];

        $this->client->request('PUT', $uri);
        self::assertResponseStatusCodeSame(422);

        $response = $this->getJsonResponse();
        self::assertEquals(['error_message' => 'Cart is Full'], $response);

        $this->client->request('GET', '/cart/1e82de36-23f3-4ae7-ad5d-616295f1d6c0');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(3, $response['products']);
    }

    public function test_refuses_to_add_product_to_cart_over_quantity(): void
    {
        $uri = '/cart/' .
            AddProductControllerFixture::CART_ID .
            '/' .
            AddProductControllerFixture::PRODUCTS_DATA[0][AddProductControllerFixture::ID_KEY];
        $this->client->request('PUT', $uri, ['quantity' => 4]);
        self::assertResponseStatusCodeSame(422);

        $response = $this->getJsonResponse();
        self::assertEquals(['error_message' => 'To not exceed the limit you can add only 3 product(s)'], $response);

        $uri = '/cart/' .
            AddProductControllerFixture::FULL_CART_ID .
            '/' .
            AddProductControllerFixture::PRODUCTS_DATA[0][AddProductControllerFixture::ID_KEY];

        $this->client->request('PUT', $uri, ['quantity' => 3]);
        self::assertResponseStatusCodeSame(422);

        $response = $this->getJsonResponse();
        self::assertEquals(['error_message' => 'Cart is Full'], $response);
    }

    public function test_returns_404_if_cart_does_not_exist(): void
    {
        $this->client->request('PUT', '/cart/8e9efe09-3f5b-4681-9f6a-adb4a5a9f19c/00e91390-3af8-4735-bd06-0311e7131757');
        self::assertResponseStatusCodeSame(404);
    }

    public function test_returns_404_if_product_does_not_exist(): void
    {
        $this->client->request('PUT', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/b832e983-6159-47db-a98f-575a46d9544c');
        self::assertResponseStatusCodeSame(404);
    }
}