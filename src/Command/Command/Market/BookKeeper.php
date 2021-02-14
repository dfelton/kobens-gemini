<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Market;

use Amp\Websocket\Client\ConnectionException;
use Kobens\Core\ConfigInterface;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Api\WebSocket\MarketData\BookKeeperFactoryInterface;
use Kobens\Gemini\Exception\Api\WebSocket\SocketSequenceException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class BookKeeper extends Command
{
    protected static $defaultName = 'market:book-keeper';

    private BookKeeperFactoryInterface $bookFactory;

    private string $lastExceptionMessage;

    public function __construct(
        BookKeeperFactoryInterface $bookKeeperFactoryInterface,
        ConfigInterface $configInterface
    ) {
        $this->bookFactory = $bookKeeperFactoryInterface;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Opens a market book.');
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Trading Symbol', 'btcusd');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $book = $this->bookFactory->create($input->getOption('symbol'));
        $loop = true;
        do {
            try {
                $book->openBook();
            } catch (ClosedBookException $e) {
                $this->outputErrorAndSleep($output, $e->getMessage());
            } catch (SocketSequenceException $e) {
                $this->outputErrorAndSleep($output, $e->getMessage());
            } catch (ConnectionException $e) {
                $this->outputErrorAndSleep($output, $e->getMessage());
            } catch (\Throwable $e) {
                $loop = false;
                do {
                    $output->writeln([
                        "Code: {$e->getCode()}",
                        "Class: " . \get_class($e),
                        "Message: {$e->getMessage()}",
                        "Trace:",
                        $e->getTraceAsString()
                    ]);
                    $e = $e->getPrevious();
                    if ($e instanceof \Throwable) {
                        $output->writeln("\nPrevious:");
                    }
                } while ($e instanceof \Throwable);
            }
        } while ($loop);
        return 0;
    }

    protected function outputErrorAndSleep(OutputInterface $output, string $message, int $seconds = 10): void
    {
        if ($seconds < 1) {
            $seconds = 10;
        }
        if (!$output->isQuiet()) {
            $output->writeln("\nERROR: $message");
            $output->write("Sleeping $seconds seconds");
        }
        for ($i = 1; $i <= $seconds; $i++) {
            if (!$output->isQuiet()) {
                $output->write('.');
            }
            \sleep(1);
        }
        if (!$output->isQuiet()) {
            $output->write(PHP_EOL);
            $output->writeln('Resuming operations');
        }
    }
}
