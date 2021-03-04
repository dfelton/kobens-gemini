<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Market;

use Amp\Websocket\Client\ConnectionException;
use Kobens\Core\ConfigInterface;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Api\WebSocket\MarketData\BookKeeperFactoryInterface;
use Kobens\Gemini\Exception\Api\WebSocket\SocketSequenceException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Amp\Websocket\ClosedException;
use Kobens\Core\Config;
use Kobens\Gemini\Command\Traits\GetNow;
use Kobens\Gemini\Exchange\Currency\Pair;
use Amp\Dns\DnsException;
use Amp\Http\Client\Connection\UnprocessedRequestException;

final class BookKeeper extends Command
{
    use GetNow;

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
            $output->writeln(sprintf(
                "%s\tStarting book keeper %s",
                $this->getNow(),
                $input->getArgument('symbol')
            ));
            $this->main($input, $output);
        }
        if ($this->shutdown()) {
            $output->writeln(sprintf(
                "%s\tShutdown signal detected - %s (%s)",
                $this->getNow(),
                self::class,
                $input->getArgument('symbol')
            ));
        }
        return $exitCode;
    }

    protected function main(InputInterface $input, OutputInterface $output): int
    {
        $symbol = Pair::getInstance($input->getArgument('symbol'))->getSymbol();
        $book = $this->bookFactory->create($symbol);
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
            $output->writeln(sprintf(
                "<fg=red>%s\tERROR: %s --- %s (%s)</>",
                $this->getNow(),
                $message,
                self::class,
                $symbol
            ));
        }
        \sleep($seconds);
        if (!$output->isQuiet()) {
            $output->writeln(sprintf(
                "%s\tResuming operations --- %s (%s)",
                $this->getNow(),
                self::class,
                $symbol
            ));
        }
    }
}
