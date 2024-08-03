<?php

declare(strict_types=1);

namespace App\Controller\Catalog;

use App\Entity\Product;
use App\Exception\EditProduct\NoNewProductValuesException;
use App\Messenger\EditProductCommandFactory;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products/{product}", methods={"PUT"}, name="product-edit")
 */
class EditController  extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(
        private EditProductCommandFactory $editProductcommandFactory,
        private ErrorBuilder $errorBuilder
    ) {}

    public function __invoke(Product $product, Request $request): Response
    {
        $name = $request->get(AddController::REQUEST_PRODUCT_NAME_KEY);
        $price = $request->get(AddController::REQUEST_PRODUCT_PRICE_KEY);

        try {
            $this->dispatch(
                $this->editProductcommandFactory->create(
                    $product,
                    \is_null($name) ? null : \trim($name),
                    \is_null($price) ? null : (int) $price
                )
            );
        } catch (NoNewProductValuesException) {
            return new JsonResponse(
                $this->errorBuilder->__invoke('No changes detected.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
