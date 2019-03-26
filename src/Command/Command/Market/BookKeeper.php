<?php

namespace Kobens\Gemini\Command\Command\Market;

use Amp\Websocket\Client\ConnectionException;
use Kobens\Core\Config;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Command\Argument\Symbol;
use Kobens\Gemini\Command\Traits\{Traits, GetSymbol};
use Kobens\Gemini\Exception\Api\WebSocket\SocketSequenceException;
use Kobens\Gemini\Exchange;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BookKeeper extends Command
{
    use Traits, GetSymbol;

    /**
     * @var \Kobens\Gemini\Api\Param\Symbol
     */
    protected $symbol;

    /**
     * @var \Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper
     */
    protected $book;

    /**
     * @var \Monolog\Logger
     */
    protected $log;

    /**
     * @var string
     */
    protected $lastExceptionMessage;

    public function __construct()
    {
        parent::__construct('market:book-keeper');
    }

    protected function configure()
    {
        $this->setDescription('Opens a market book.');
        $this->addArgList([new Symbol()], $this);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->symbol = $this->getSymbol($input)->getValue();
        $this->book = (new Exchange())->getBookKeeper($this->symbol);
        $this->log = new Logger($this->symbol);
        $this->log->pushHandler(new StreamHandler(
            \sprintf(
                '%s/var/log/gemini_market_book_%d.log',
                (new Config())->getRoot(),
                \getmypid()
            ),
            Logger::INFO
        ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
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


    /**
     * @todo finish me
     * @todo move to single purpose class object
     *
     * @return bool
     */
    protected function isMaintenance() : bool
    {
        return false;
        $ch = \curl_init(\sprintf('https://%s', (new Config())->gemini->api->host));
        \curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => false,  // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => "",     // handle compressed
            CURLOPT_USERAGENT      => "test", // name of client
            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT        => 120,    // time-out on response
        ]);
    }

}
