<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Market;

use Kobens\Exchange\ExchangeInterface;
use Kobens\Exchange\Book\BookInterface;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Command\Traits\Traits;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class Watcher extends Command
{
    use Traits;

    private const REFRESH_DEFAULT = 500000; // micro seconds
    private const REFRESH_MIN     = 100000; // micro seconds

    protected static $defaultName = 'market:watcher';

    private ExchangeInterface $exchange;

    private HostInterface $host;

    private string $symbol;

    private bool $hasReportedClosedBook = false;

    private bool $bookIsOpen = false;

    private ?string $ask = null;

    private ?string $bid = null;

    private ?string $spread = null;

    private int $lastOutput = 0;

    private int $tabLength = 8;

    private int $refreshRate;

    private BookInterface $book;

    public function __construct(
        ExchangeInterface $exchangeInterface,
        HostInterface $hostInterface
    ) {
        $this->exchange = $exchangeInterface;
        $this->host = $hostInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Outputs details on a market book.');
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Trading Pair Symbol', 'btcusd');
        $this->addOption('refresh', 'r', InputOption::VALUE_OPTIONAL, 'Refresh rate in micro seconds', self::REFRESH_DEFAULT);
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
                        $this->host->getHost()
                    ));
                }
                $output->write('<fg=red>.</>');
                \sleep(1);
                continue;
            }
            if (
                $this->ask !== $ask ||
                $this->bid !== $bid ||
                $this->spread !== $spread ||
                $time - $this->lastOutput > 0
            ) {
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
        $this->symbol = $input->getOption('symbol');
        $this->refreshRate = (int) $input->getOption('refresh');
        if ($this->refreshRate < self::REFRESH_MIN) {
            $this->refreshRate = self::REFRESH_DEFAULT;
        }
        $this->book = $this->exchange->getBook($this->symbol);
    }

    private function outputUpdate(OutputInterface $output): void
    {
        $this->clearTerminal($output);
        $table = new Table($output);
        $table->setRows([
            ['Date', $this->getNow()],
            ['Host', $this->host->getHost()],
            ['Symbol', strtoupper($this->symbol)],
            ['Ask', "<fg=red>{$this->ask}</>"],
            ['Bid', "<fg=green>{$this->bid}</>"],
            ['Spread', $this->spread],
        ])->render();
    }
}
