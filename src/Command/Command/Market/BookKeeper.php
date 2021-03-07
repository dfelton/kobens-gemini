<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Market;

use Amp\Dns\DnsException;
use Amp\Http\Client\Connection\UnprocessedRequestException;
use Amp\Websocket\Client\ConnectionException;
use Amp\Websocket\ClosedException;
use Kobens\Core\ConfigInterface;
use Kobens\Core\Config;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Command\Traits\GetNow;
use Kobens\Gemini\Command\Traits\Output;
use Kobens\Gemini\Api\WebSocket\MarketData\BookKeeperFactoryInterface;
use Kobens\Gemini\Exception\Api\WebSocket\SocketSequenceException;
use Kobens\Gemini\Exchange\Currency\Pair;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BookKeeper extends Command
{
    use GetNow;
    use Output;

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
        $this->addArgument('symbol', InputArgument::OPTIONAL, 'Trading Symbol', 'btcusd');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        if (!$this->shutdown()) {
            $this->main($input, $output);
        }
        if ($this->shutdown()) {
            $this->writeError(sprintf('Shutdown signal detected (%s)', $input->getArgument('symbol')), $output);
        }
        return $exitCode;
    }

    protected function main(InputInterface $input, OutputInterface $output): int
    {
        $symbol = Pair::getInstance($input->getArgument('symbol'))->getSymbol();
        $book = $this->bookFactory->create($symbol);

        // TODO: this method is not part of the interface
        /** @var \Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper $book */
        if ($book->isOpen()) {
            $this->writeError(sprintf('Can only open a closed book (%s)', $symbol), $output);
            return 1;
        }

        $this->writeNotice(sprintf('Starting book keeper (%s)', $symbol), $output);

        do {
            try {
                $book->openBook();
            } catch (ClosedBookException | SocketSequenceException | ConnectionException | ClosedException | DnsException | UnprocessedRequestException $e) {
                $this->outputErrorAndSleep($symbol, $output, $e->getMessage());
            } catch (\Throwable $e) {
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
                return 1;
            }
        } while ($this->shutdown() === false);
        return 0;
    }

    private function shutdown(): bool
    {
        return file_exists(
            Config::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'kill_market_book'
        );
    }

    private function outputErrorAndSleep(string $symbol, OutputInterface $output, string $message, int $seconds = 60): void
    {
        if ($seconds < 1) {
            $seconds = 10;
        }
        if (!$output->isQuiet()) {
            $this->writeError(sprintf('%s (%s)', $message, $symbol), $output);
        }
        \sleep($seconds);
        if (!$output->isQuiet()) {
            $this->writeNotice(sprintf('%s (%s)', 'Resuming Operations', $symbol), $output);
        }
    }
}
