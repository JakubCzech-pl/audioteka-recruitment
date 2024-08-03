<?php

namespace App\ResponseBuilder;

use App\Controller\Catalog\ListController;
use App\Service\Catalog\ProductListProviderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductListBuilder
{
    private const PREVIOUS_PAGE_KEY = 'previous_page';
    private const NEXT_PAGE_KEY = 'next_page';
    private const TOTAL_PRODUCTS_COUNT_KEY = 'count';
    private const PRODUCTS_KEY = 'products';

    public function __construct(
        private ProductListProviderInterface $productListProvider,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function __invoke(string $routeName, int $page): array
    {
        $totalCount = $this->productListProvider->getTotalCount();

        return [
            self::PREVIOUS_PAGE_KEY => $this->getPreviousPageUrl($routeName, $page),
            self::NEXT_PAGE_KEY => $this->getNextPageUrl($routeName, $page, $totalCount),
            self::TOTAL_PRODUCTS_COUNT_KEY => $totalCount,
            self::PRODUCTS_KEY => $this->getProducts($page)
        ];
    }

    private function getPreviousPageUrl(string $routeName, int $page): ?string
    {
        if ($page <= 0) {
            return null;
        }

        return $this->urlGenerator->generate(
            $routeName,
            [ListController::PAGE_REQUEST_PARAM_KEY => $page - 1]
        );
    }

    private function getNextPageUrl(string $routeName, int $page, int $totalCount): ?string
    {
        $lastPage = ceil($totalCount / ProductListProviderInterface::MAX_PER_PAGE);
        if ($page < $lastPage - 1) {
            return $this->urlGenerator->generate(
                $routeName,
                [ListController::PAGE_REQUEST_PARAM_KEY => $page + 1]);
        }

        return null;
    }

    private function getProducts(int $page): array
    {
        return \iterator_to_array(
            $this->productListProvider->getList($page)->getProducts()
        );
    }
}
