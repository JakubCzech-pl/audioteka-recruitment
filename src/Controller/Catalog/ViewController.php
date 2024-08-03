<?php

declare(strict_types=1);

namespace App\Controller\Catalog;

use App\Entity\Product;
use App\ResponseBuilder\ProductViewBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product/{product}", methods={"GET"}, name="product-view")
 */
class ViewController extends AbstractController
{
    public function __construct(private ProductViewBuilder $productViewBuilder) {}

    public function __invoke(Product $product, Request $request): Response
    {
        return new JsonResponse(
            $this->productViewBuilder->__invoke($product),
            Response::HTTP_OK
        );
    }
}
