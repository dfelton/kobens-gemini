<?php

namespace Kobens\Gemini\Command\Command\Order;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Api\Websocket\OrderEvents\BookKeeper as OrderBookKeeper;

class BookKeeper extends Command
{
    public function configure()
    {
        $this->setName('order:book-keeper');
        $this->setDescription('Keeps a copy of private order book data in cache');
    }

    public function execute(InputInterface $input, OutputInterface $output)
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