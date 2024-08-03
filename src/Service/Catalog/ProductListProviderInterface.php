<?php

namespace App\Service\Catalog;

interface ProductListProviderInterface
{
    public const MAX_PER_PAGE = 3;

    public function getList(int $page = 0): ProductListInterface;

    public function getTotalCount(): int;
}
