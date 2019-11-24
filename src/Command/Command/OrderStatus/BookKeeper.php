<?php

namespace Kobens\Gemini\Command\Command\OrderStatus;

use Kobens\Gemini\Api\WebSocket\OrderEvents\BookKeeperInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BookKeeper extends Command
{
    protected static $defaultName = 'order-status:book-keeper';

    /**
     * @var BookKeeperInterface
     */
    private $book;

    public function __construct(BookKeeperInterface $bookKeeperInterface)
    {
        $this->book = $bookKeeperInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Maintains cache of private order book data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->book->openBook();
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln($e->getTraceAsString());
            exit(1);
        }
    }
}
