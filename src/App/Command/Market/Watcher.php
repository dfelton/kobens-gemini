<?php

namespace Kobens\Gemini\App\Command\Market;

use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\App\Command\CommandTraits;
use Kobens\Gemini\App\Config;
use Kobens\Gemini\Exchange;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Exchange\Pair\BTCUSD;

class Watcher extends Command
{
    use CommandTraits;

    const REFRESH_RATE_DEFAULT = 500000;
    const REFRESH_RATE_MIN = 100000;

    protected static $defaultName = 'market:watcher';

    protected function configure()
    {
        $this->setDescription('Outputs details on a market book.');
        $this->addArgument(
            'symbol',
            InputArgument::OPTIONAL,
            'Trading pair symbol',
            (new BTCUSD())->getPairSymbol()
        );
        $this->addArgument(
            'refresh_rate',
            InputArgument::OPTIONAL,
            'Refresh rate in micro seconds',
            static::REFRESH_RATE_DEFAULT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $book = (new Exchange())->getBook($input->getArgument('symbol'));
        $host = (new Config())->gemini->api->host;
        $refreshRate = $this->getRefreshRate($input, $output);
        $lastAsk = null;
        $lastBid = null;
        $lastOutput = 0;
        $bookIsOpen = false;
        $hasReportedClosedBook = false;

        while (true) {
            try {
                $ask = $book->getAskPrice();
                $bid = $book->getBidPrice();
            } catch (ClosedBookException $e) {
                if ($bookIsOpen || $hasReportedClosedBook === false) {
                    $hasReportedClosedBook = true;
                    $bookIsOpen = false;
                    $this->clearTerminal();
                    $output->write(\sprintf(
                        'Book "%s" on "%s" is closed.',
                        $book->getSymbol(),
                        $host
                    ));
                }
                $this->sleep($output);
                continue;
            }

            $time = \time();
            if ($lastAsk !== $ask || $lastBid !== $bid || $time - $lastOutput > 0) {
                $lastOutput = $time;
                $lastAsk = $ask;
                $lastBid = $bid;
                $this->clearTerminal();
                $output->writeln([
                    "Date:\t".$this->getNow(),
                    "Host:\t".$host,
                    "Symbol:\t".$book->getSymbol(),
                    "Bid:\t".$bid,
                    "Ask:\t".$ask,
                    "Spread:\t".$book->getSpread(),
                    PHP_EOL,
                ]);
            }
            $bookIsOpen = true;
            \usleep($refreshRate);
        }
    }

    protected function getRefreshRate(InputInterface $input, OutputInterface $output) : int
    {
        $refreshRate = (int) $input->getArgument('refresh_rate');
        if ($refreshRate < static::REFRESH_RATE_MIN) {
            $output->writeln(\sprintf(
                'Warning, minimum refresh rate is "%d". "%d" provided. Reverting to default refresh rate',
                static::REFRESH_RATE_MIN,
                static::REFRESH_RATE_DEFAULT
            ));
            $refreshRate = static::REFRESH_RATE_DEFAULT;
        }
        return $refreshRate;
    }

}
