<?php

declare(strict_types=1);

namespace App\Messenger;

class EditProduct
{
    public function __construct(
        public readonly string $productId,
        public readonly string $name,
        public readonly int $price
    ) {}
}
