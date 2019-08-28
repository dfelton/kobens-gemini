<?php

namespace Kobens\Gemini\Command\Command\Market;

use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Exchange;
use Kobens\Gemini\Command\Argument\RefreshRate;
use Kobens\Gemini\Command\Argument\Symbol;
use Kobens\Gemini\Command\Traits\GetRefreshRate;
use Kobens\Gemini\Command\Traits\GetSymbol;
use Kobens\Gemini\Command\Traits\Traits;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Watcher extends Command
{
    use GetRefreshRate, GetSymbol, Traits;

    protected static $defaultName = 'gemini:market:watcher';

    private $isInitialized = false;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var bool
     */
    private $hasReportedClosedBook = false;

    /**
     * @var bool
     */
    private $bookIsOpen = false;

    /**
     * @var string
     */
    private $ask;

    /**
     * @var string
     */
    private $bid;

    /**
     * @var string
     */
    private $spread;

    /**
     * @var int
     */
    private $lastOutput = 0;

    /**
     * @var int
     */
    private $tabLength = 8;

    /**
     * @var int
     */
    private $refreshRate = RefreshRate::DEFAULT;

    /**
     * @var \Kobens\Exchange\Book\BookInterface
     */
    private $book;

    protected function configure()
    {
        $this->setDescription('Outputs details on a market book.');
        $this->addArgList([new Symbol(), new RefreshRate()], $this);
        $this->addArgument('tab_length', InputArgument::OPTIONAL, 'Terminal Tab Length', 8);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input, $output);

        while (true) {

            $time = \time();

            try {
                $ask = $this->book->getAskPrice();
                $bid = $this->book->getBidPrice();
                $spread = $this->book->getSpread();
            } catch (ClosedBookException $e) {
                if (   $this->bookIsOpen === true
                    || $this->hasReportedClosedBook === false
                    || $time % 30 === 0
                ) {
                    $this->hasReportedClosedBook = true;
                    $this->bookIsOpen = false;
                    $this->clearTerminal($output);
                    $output->write(\sprintf(
                        '<fg=red>Book "%s" on "%s" is closed.</>',
                        $this->symbol,
                        $this->getHost()
                    ));
                }
                $output->write('<fg=red>.</>');
                \sleep(1);
                continue;
            }

            if (   $this->ask !== $ask
                || $this->bid !== $bid
                || $this->spread !== $spread
                || $time - $this->lastOutput > 0) {
                $this->lastOutput = $time;
                $this->ask = $ask;
                $this->bid = $bid;
                $this->spread = $spread;
                $this->outputUpdate($output);
            }
            $this->bookIsOpen = true;
            \usleep($this->refreshRate);
        }
    }

    private function init(InputInterface $input, OutputInterface $output): void
    {
        if ($this->isInitialized) {
            throw new \Exception(\sprintf('Cannot initialize "%" more than once', __CLASS__));
        }
        $this->symbol = $this->getSymbol($input)->getValue();
        $this->refreshRate = $this->getRefreshRate($input, $output)->getValue();
        $tabLength = (int) $input->getArgument('tab_length');
        if ($tabLength <= 0) {
            $tabLength = 0;
        }
        $this->tabLength = $tabLength;
        $this->book = (new Exchange())->getBook($this->symbol);
    }

    private function outputUpdate(OutputInterface $output): void
    {
        $tabsBid = 3 - \floor(\strlen($this->bid) / $this->tabLength);
        if ($tabsBid < 0) {
            $tabsBid = 0;
        }

        $tabsAsk = 3 - \floor(\strlen($this->ask) / $this->tabLength);
        if ($tabsAsk < 0) {
            $tabsAsk = 0;
        }

        $tabsSpread = 3 - \floor(\strlen($this->spread) / $this->tabLength);
        if ($tabsSpread < 0) {
            $tabsSpread = 0;
        }

        $this->clearTerminal($output);
        $output->writeln([
            \str_repeat('-', 41),
            \sprintf("- Date:\t\t%s\t-", $this->getNow()),
            \sprintf("- Host:\t\t%s\t-", $this->getHost()),
            \sprintf("- Symbol:\t%s\t\t\t-", \strtoupper($this->symbol)),
            "-\t\t\t\t\t-",
            \sprintf("- Ask:\t\t<fg=red>%s</>%s-", $this->ask, \str_repeat("\t", $tabsAsk)),
            \sprintf("- Bid:\t\t<fg=green>%s</>%s-", $this->bid, \str_repeat("\t", $tabsBid)),
            \sprintf("- Spread:\t%s%s-", $this->spread, \str_repeat("\t", $tabsSpread)),
            \str_repeat('-', 41),
        ]);
    }
}
