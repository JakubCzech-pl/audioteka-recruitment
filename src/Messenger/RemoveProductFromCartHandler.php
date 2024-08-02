<?php

namespace App\Messenger;

use App\Service\Cart\CartServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveProductFromCartHandler implements MessageHandlerInterface
{
    public function __construct(private CartServiceInterface $service) {}

    public function __invoke(RemoveProductFromCart $command): void
    {
        $this->service->removeProduct($command->cartId, $command->cartProductId);
    }
}
