<?php

namespace App\Controller\Catalog;

use App\Messenger\AddProductToCatalog;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products", methods={"POST"}, name="product-add")
 */
class AddController extends AbstractController implements MessageBusAwareInterface
{
    public const REQUEST_PRODUCT_NAME_KEY = 'name';
    public const REQUEST_PRODUCT_PRICE_KEY = 'price';

    use MessageBusTrait;

    public function __construct(private ErrorBuilder $errorBuilder) { }

    public function __invoke(Request $request): Response
    {
        $name = trim($request->get(self::REQUEST_PRODUCT_NAME_KEY));
        $price = (int) $request->get(self::REQUEST_PRODUCT_PRICE_KEY);

        if ($name === '' || $price < 1) {
            return new JsonResponse(
                $this->errorBuilder->__invoke('Invalid name or price.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->dispatch(new AddProductToCatalog($name, $price));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}