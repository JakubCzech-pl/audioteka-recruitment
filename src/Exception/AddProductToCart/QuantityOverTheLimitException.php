<?php

declare(strict_types=1);

namespace App\Exception\AddProductToCart;

class QuantityOverTheLimitException extends \Exception
{
    public function __construct(int $quantityLeft, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(
            \sprintf('To not exceed the limit you can add only %s product(s)', $quantityLeft),
            $code,
            $previous
        );
    }
}