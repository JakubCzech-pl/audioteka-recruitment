<?php

namespace App\Service\Catalog;

interface ProductService
{
    public function add(string $name, int $price): ProductInterface;

    public function remove(string $id): void;
}
