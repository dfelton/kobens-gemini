<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\CliMonitor;

use Kobens\Core\Config;
use Kobens\Core\SleeperInterface;
use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Exchange\Currency\Pair as CurrencyPair;
use Kobens\Gemini\TradeRepeater\Watcher\Helper\Data;
use Kobens\Gemini\TradeRepeater\Watcher\MarketSpread;
use Kobens\Gemini\TradeRepeater\Watcher\TradeSpread;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class Pair extends Command
{
    private const KILL_FILE = '/var/kill_monitor';

    private const REFRESH_DEFAULT = 10;
    private const REFRESH_MIN = 5;
    private const REFRESH_MAX = 3600;

    protected static $defaultName = 'repeater:monitor:pair';

    private SleeperInterface $sleeper;

    private Data $data;

    public function __construct(
        Data $data,
        SleeperInterface $sleeperInterface
    ) {
        $this->data = $data;
        $this->sleeper = $sleeperInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Monitoring output on trades and the market');
        $this->addOption('pair', 'p', InputOption::VALUE_OPTIONAL, 'Comma separated trading pair(s) to show trade-repeater data for', 'btcusd');
        $this->addOption('disable-loop', null, InputOption::VALUE_OPTIONAL, 'Disable continous output', false);
        $this->addOption(
            'refresh',
            'r',
            InputOption::VALUE_OPTIONAL,
            sprintf(
                'Refresh rate in seconds (min %s; max %s)',
                self::REFRESH_MIN,
                self::REFRESH_MAX
            ),
            self::REFRESH_DEFAULT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sleep = $this->getRefreshRate($input);
        $loop = $input->getOption('disable-loop') === false;
        do {
            try {
                $this->main($input, $output);
                $this->data->reset();
                if ($loop) {
                    $this->sleeper->sleep($sleep, static function () {
                    });
                }
            } catch (\Throwable $e) {
                $loop = false;
                do {
                    $output->writeln([
                        'Code: ' . $e->getCode(),
                        'Class: ' . \get_class($e),
                        'Message: ' . $e->getMessage(),
                        "Trace:\n" . $e->getTraceAsString(),
                    ]);
                    $e = $e->getPrevious();
                    if ($e instanceof \Throwable) {
                        $output->writeln("\nPrevious:");
                    }
                } while ($e instanceof \Throwable);
            }
        } while ($loop && !file_exists(Config::getInstance()->getRootDir() . self::KILL_FILE));
    }

    /**
     * @return PairInterface[]
     */
    private function getPairs(InputInterface $input, string $option): array
    {
        $pairs = [];
        if ($input->getOption($option) !== null) {
            foreach (explode(',', $input->getOption($option)) as $symbol) {
                $pairs[] = CurrencyPair::getInstance(strtolower(trim($symbol)));
            }
        }
        return $pairs;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param bool $showMarketData
     * @param bool $showNotional
     * @param bool $showAvailable
     * @param bool $showAvailableNotional
     */
    private function main(InputInterface $input, OutputInterface $output): void
    {
        $data = $this->getData($input, $output);
        $time = (new Table($output))->setRows([['Date / Time:', (new \DateTime())->format('Y-m-d H:i:s')]]);

        $output->write("\e[H\e[J");
        $time->render();
        foreach ($data as $table) {
            $table->render();
        }
    }

    private function getRefreshRate(InputInterface $input): int
    {
        $sleep = (int) $input->getOption('refresh');
        if ($sleep < self::REFRESH_MIN || $sleep > self::REFRESH_MAX) {
            $sleep = self::REFRESH_DEFAULT;
        }
        return $sleep;
    }

    /**
     * @param OutputInterface $output
     * @param PairInterface[] $pairs
     * @param bool $showMarketData
     * @return Table[]
     */
    private function getData(InputInterface $input, OutputInterface $output): array
    {
        $data = [];
        try {
            foreach ($this->getPairs($input, 'pair') as $pair) {
                $data[] = TradeSpread::getTable($output, $this->data, $pair->getSymbol());
                $data[] = MarketSpread::getTable($output, $this->data, $pair->getSymbol());
            }
        } catch (\Kobens\Gemini\Exception $e) {
            $data[] = (new Table($output))->addRow([$e->getMessage()]);
        }
        return $data;
    }
}