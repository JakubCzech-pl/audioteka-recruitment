<?php

namespace App\Controller\Catalog;

use App\ResponseBuilder\ProductListBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products", methods={"GET"}, name="product-list")
 */
class ListController extends AbstractController
{
    public const PAGE_REQUEST_PARAM_KEY = 'page';

    private const ROUTE_NAME = 'product-list';

    public function __construct(private ProductListBuilder $productListBuilder) {}

    public function __invoke(Request $request): Response
    {
        return new JsonResponse(
            $this->productListBuilder->__invoke(self::ROUTE_NAME, $this->getPageFromRequest($request)),
            Response::HTTP_OK
        );
    }

    private function getPageFromRequest(Request $request): int
    {
        return \max(
            0,
            (int) $request->get(self::PAGE_REQUEST_PARAM_KEY, 0)
        );
    }
}
