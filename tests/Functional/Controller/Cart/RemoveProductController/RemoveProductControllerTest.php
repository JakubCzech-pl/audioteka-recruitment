<?php

namespace App\Tests\Functional\Controller\Cart\RemoveProductController;

use App\Tests\Functional\WebTestCase;

class RemoveProductControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new RemoveProductControllerFixture());
    }

    public function test_removes_product_form_cart(): void
    {
        $uri = '/cart/' .
            RemoveProductControllerFixture::CART_ID .
            '/' .
            RemoveProductControllerFixture::CART_PRODUCT_ID_TO_DELETE;
        $this->client->request('DELETE', $uri);
        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/cart/' . RemoveProductControllerFixture::CART_ID);
        self::assertResponseStatusCodeSame(200);
        $response = $this->getJsonResponse();
        self::assertCount(0, $response['products']);
    }

    public function test_ignores_request_if_product_is_not_in_cart(): void
    {
        $uri = '/cart/' .
            RemoveProductControllerFixture::CART_ID .
            '/' .
            '7bcf6fe9-e831-4776-a9df-76a702233adc';
        $this->client->request('DELETE', $uri);
        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/cart/' . RemoveProductControllerFixture::CART_ID);
        self::assertResponseStatusCodeSame(200);
        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);
    }

    public function test_returns_404_if_cart_does_not_exist(): void
    {
        $this->client->request('DELETE', '/cart/46750c8e-41fe-4046-b237-8867cdb62a75/d11e1e69-cca7-40a1-8273-9d93c8346efd');
        self::assertResponseStatusCodeSame(404);
    }
}