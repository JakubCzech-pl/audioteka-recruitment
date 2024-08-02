<?php

declare(strict_types=1);

namespace App\Exception\AddProductToCart;

class CartIsFullException extends \Exception
{
    public function __construct(string $message = 'Cart is Full', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}