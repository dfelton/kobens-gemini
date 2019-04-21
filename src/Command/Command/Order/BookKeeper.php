<?php

namespace Kobens\Gemini\Command\Command\Order;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Api\Websocket\OrderEvents\BookKeeper as OrderBookKeeper;

final class BookKeeper extends Command
{
    protected static $defaultName = 'kobens:gemini:order:book-keeper';

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
            \Zend\Debug\Debug::dump($e->getTraceAsString());
        }
    }
}