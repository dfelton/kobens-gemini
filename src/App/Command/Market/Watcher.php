<?php

namespace Kobens\Gemini\App\Command\Market;

use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\App\Command\Argument\{RefreshRate, Symbol};
use Kobens\Gemini\App\Command\Traits\{CommandTraits, GetRefreshRate};
use Kobens\Gemini\App\Config;
use Kobens\Gemini\Exchange;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Watcher extends Command
{
    use CommandTraits;
    use GetRefreshRate;

    protected static $defaultName = 'market:watcher';

    protected function configure()
    {
        $this->setDescription('Outputs details on a market book.');
        $this->addArgList([new Symbol(), new RefreshRate()], $this);
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
                $spread = $book->getSpread();
            } catch (ClosedBookException $e) {
                if ($bookIsOpen || $hasReportedClosedBook === false) {
                    $hasReportedClosedBook = true;
                    $bookIsOpen = false;
                    $this->clearTerminal($output);
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
                $this->clearTerminal($output);
                $output->writeln([
                    "Date:\t".$this->getNow(),
                    "Host:\t".$host,
                    "Symbol:\t".$book->getSymbol(),
                    "Bid:\t".$bid,
                    "Ask:\t".$ask,
                    "Spread:\t".$spread,
                    PHP_EOL,
                ]);
            }
            $bookIsOpen = true;
            \usleep($refreshRate->getValue());
        }
    }

}
