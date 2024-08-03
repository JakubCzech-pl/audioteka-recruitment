<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Service\Catalog\ProductServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EditProductHandler implements MessageHandlerInterface
{
    public function __construct(private ProductServiceInterface $productService) {}

    public function __invoke(EditProduct $command): void
    {
        $this->productService->edit($command->productId, $command->name, $command->price);
    }
}
