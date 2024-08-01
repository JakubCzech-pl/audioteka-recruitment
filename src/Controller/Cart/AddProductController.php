<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\Product;
use App\Messenger\AddProductToCartCommandFactory;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart/{cart}/{product}", methods={"PUT"}, name="cart-add-product")
 */
class AddProductController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(
        private ErrorBuilder $errorBuilder,
        private AddProductToCartCommandFactory $addProductToCartCommandFactory
    ) {}

    public function __invoke(Cart $cart, Product $product, Request $request): Response
    {
        try {
            $this->dispatch($this->addProductToCartCommandFactory->create(
                $cart,
                $product,
                $this->getQuantityFromRequest($request)
            ));
        } catch (\Exception $exception) {
            return new JsonResponse(
                $this->errorBuilder->__invoke($exception->getMessage()),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return new Response('', Response::HTTP_ACCEPTED);
    }

    private function getQuantityFromRequest(Request $request): int
    {
        return (int) $request->get('quantity', 1);
    }
}
