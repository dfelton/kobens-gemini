<?php

namespace Kobens\Gemini\Command\Command\OrderStatus;

use Kobens\Gemini\Api\WebSocket\OrderEvents\BookKeeper as OrderBookKeeper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BookKeeper extends Command
{
    protected static $defaultName = 'order-status:book-keeper';

    protected function configure()
    {
        $this->setDescription('Maintains cache of private order book data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $book = new OrderBookKeeper();
        try {
            $book->openBook();
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln($e->getTraceAsString());
            exit(1);
        }
    }
}