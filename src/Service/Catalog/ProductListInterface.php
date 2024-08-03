<?php

declare(strict_types=1);

namespace App\Service\Catalog;

interface ProductListInterface
{
    public function getProducts(): \Generator;
}
