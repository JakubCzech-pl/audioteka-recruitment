<?php

namespace App\Service\Catalog;

interface ProductServiceInterface
{
    public function add(string $name, int $price): void;

    public function edit(string $productId, string $name, int $price): void;

    public function remove(string $productId): void;
}
