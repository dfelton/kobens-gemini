<?php

namespace Kobens\Gemini\Command\Command\Market;

use Kobens\Core\Config;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Command\Argument\{RefreshRate, Symbol};
use Kobens\Gemini\Command\Traits\{CommandTraits, GetRefreshRate, GetSymbol};
use Kobens\Gemini\Exchange;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Api\Host;

class Watcher extends Command
{
    use CommandTraits;
    use GetRefreshRate, GetSymbol;

    protected static $defaultName = 'market:watcher';

    protected function configure()
    {
        $this->setDescription('Outputs details on a market book.');
        $this->addArgList([new Symbol(), new RefreshRate()], $this);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symbol = $this->getSymbol($input)->getValue();
        $book = (new Exchange())->getBook($symbol);
        $refreshRate = $this->getRefreshRate($input, $output)->getValue();
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
                        '<fg=red>Book "%s" on "%s" is closed.</>',
                        $book->getSymbol(),
                        $this->getHost()
                    ));
                }
                $this->sleep($output, 1);
                continue;
            }

            $time = \time();
            if ($lastAsk !== $ask || $lastBid !== $bid || $time - $lastOutput > 0) {
                $lastOutput = $time;
                $lastAsk = $ask;
                $lastBid = $bid;
                $this->outputUpdate($output, $symbol, $bid, $ask, $spread);
            }
            $bookIsOpen = true;
            \usleep($refreshRate);
        }
    }

    protected function outputUpdate(OutputInterface $output, string $symbol, string $bid, string $ask, string $spread)
    {

        $tabsBid = 3 - \floor(\strlen($bid)/8);
        if ($tabsBid < 0) {
            $tabsBid = 0;
        }

        $tabsAsk = 3 - \floor(\strlen($ask)/8);
        if ($tabsAsk < 0) {
            $tabsAsk = 0;
        }

        $tabsSpread = 3 - \floor(\strlen($spread)/8);
        if ($tabsSpread < 0) {
            $tabsSpread = 0;
        }

        $this->clearTerminal($output);
        $output->writeln([
            \str_repeat('-', 41),
            \sprintf("- Date:\t\t%s\t-", $this->getNow()),
            \sprintf("- Host:\t\t%s\t-", $this->getHost()),
            \sprintf("- Symbol:\t%s\t\t\t-", $symbol),
            "-\t\t\t\t\t-",
            \sprintf("- Ask:\t\t<fg=red>%s</>%s-", $ask, \str_repeat("\t", $tabsAsk)),
            \sprintf("- Bid:\t\t<fg=green>%s</>%s-", $bid, \str_repeat("\t", $tabsBid)),
            \sprintf("- Spread:\t%s%s-", $spread, \str_repeat("\t", $tabsSpread)),
            \str_repeat('-', 41),
        ]);
    }

}
