<?php

namespace Kobens\Gemini\Command\Command\Market;

use Amp\Websocket\Client\ConnectionException;
use Kobens\Core\Config;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Exchange;
use Kobens\Gemini\Command\Argument\Symbol;
use Kobens\Gemini\Command\Traits\GetSymbol;
use Kobens\Gemini\Command\Traits\Traits;
use Kobens\Gemini\Exception\Api\WebSocket\SocketSequenceException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BookKeeper extends Command
{
    use Traits, GetSymbol;

    protected static $defaultName = 'market:book-keeper';

    /**
     * @var \Kobens\Gemini\Api\Param\Symbol
     */
    private $symbol;

    /**
     * @var \Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper
     */
    private $book;

    /**
     * @var \Monolog\Logger
     */
    private $log;

    /**
     * @var string
     */
    private $lastExceptionMessage;

    protected function configure()
    {
        $this->setDescription('Opens a market book.');
        $this->addArgList([new Symbol()], $this);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->symbol = $this->getSymbol($input)->getValue();
        $this->book = (new Exchange())->getBookKeeper($this->symbol);
        $this->log = new Logger($this->symbol);
        $this->log->pushHandler(new StreamHandler(
            \sprintf(
                '%s/var/log/market_book_%d.log',
                Config::getInstance()->getRootDir(),
                \getmypid()
            ),
            Logger::INFO
        ));

        $loop = true;
        $this->log->info(\sprintf('Opening "%s" on "%s"', $this->symbol, $this->getHost()));
        do {
            try {
                $this->book->openBook();
            } catch (ClosedBookException $e) {
                $this->logException($e, false);
                $this->outputErrorAndSleep($output, $e->getMessage());
            } catch (SocketSequenceException $e) {
                $this->logException($e);
                $this->outputErrorAndSleep($output, $e->getMessage());
            } catch (ConnectionException $e) {
                $this->logException($e);
                $this->outputErrorAndSleep($output, $e->getMessage());
            } catch (\Exception $e) {
                $loop = false;
                $this->logException($e);
            }
        } while ($loop);
    }

    protected function outputErrorAndSleep(OutputInterface $output, string $message, int $seconds = 10)
    {
        if ($seconds < 1) {
            $seconds = 10;
        }
        if (!$output->isQuiet()) {
            $output->write(PHP_EOL);
            $output->writeln("ERROR: $message");
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

    protected function logException(\Exception $e, $logNewTrace = true) : void
    {
        $this->log->warning(\json_encode([
            'symbol' => $this->symbol,
            'errClass' => \get_class($e),
            'errCode' => $e->getCode(),
            'errMessage' => $e->getMessage(),
        ]));
        if ($logNewTrace && $this->lastExceptionMessage !== $e->getMessage()) {
            $this->lastExceptionMessage = $e->getMessage();
            $this->log->warning($e->getTraceAsString());
        }
    }
}
