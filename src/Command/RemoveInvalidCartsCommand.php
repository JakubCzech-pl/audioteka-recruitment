<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\CartRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cart:clear-invalid',
    description: 'Removes all Carts that contains Products over the limit',
    hidden: false
)]
class RemoveInvalidCartsCommand extends Command
{
    public function __construct(private CartRepository $cartRepository, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cartRepository->deleteCartsOverTheLimit();

        return Command::SUCCESS;
    }
}