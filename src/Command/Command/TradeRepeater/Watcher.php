<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\Config;
use Kobens\Core\SleeperInterface;
use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Watcher\AccountNotionalBalance;
use Kobens\Gemini\TradeRepeater\Watcher\Balances;
use Kobens\Gemini\TradeRepeater\Watcher\Helper\Data;
use Kobens\Gemini\TradeRepeater\Watcher\MarketSpread;
use Kobens\Gemini\TradeRepeater\Watcher\TradeSpread;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class Watcher extends Command
{
    private const REFRESH_DEFAULT = 10;
    private const REFRESH_MIN = 5;
    private const REFRESH_MAX = 3600;

    protected static $defaultName = 'trade-repeater:watcher';

    private SleeperInterface $sleeper;

    private Data $data;

    private GetPriceInterface $getPrice;

    public function __construct(
        Data $data,
        SleeperInterface $sleeperInterface,
        GetPriceInterface $getPrice
    ) {
        $this->data = $data;
        $this->sleeper = $sleeperInterface;
        $this->getPrice = $getPrice;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Monitoring output on trades and the market');
        $this->addOption('available', 'a', InputOption::VALUE_OPTIONAL, 'Show available for each balance', false);
        $this->addOption('available_notional', 'A', InputOption::VALUE_OPTIONAL, 'Show available notional for each balance', false);
        $this->addOption('notional', 'N', InputOption::VALUE_OPTIONAL, 'Show notional amount for each balance', false);
        $this->addOption('price', 'p', InputOption::VALUE_OPTIONAL, 'Comma separated trading pair(s) to show ask price for');
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Comma separated trading pair(s) to show trade-repeater data for');
        $this->addOption('market', 'm', InputOption::VALUE_OPTIONAL, 'Show Market Data', false);
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
        $this->addOption(
            'loop',
            'l',
            InputOption::VALUE_OPTIONAL,
            'Enable looping and continuous output',
            false
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sleep = $this->getRefreshRate($input);
        $showMarketData = $input->getOption('market') === false ? false : true;
        $showNotional = $input->getOption('notional') === false ? false : true;
        $showAvailable = $input->getOption('available') === false ? false : true;
        $showAvailableNotional = $input->getOption('available_notional') === false ? false : true;
        $loop = $input->getOption('loop') === false ? false : true;
        do {
            try {
                $this->main(
                    $input,
                    $output,
                    $showMarketData,
                    $showNotional,
                    $showAvailable,
                    $showAvailableNotional
                );
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
        } while ($loop);
    }

    /**
     * @return PairInterface[]
     */
    private function getPairs(InputInterface $input, string $option): array
    {
        $pairs = [];
        if ($input->getOption($option) !== null) {
            foreach (explode(',', $input->getOption($option)) as $symbol) {
                $pairs[] = Pair::getInstance(strtolower(trim($symbol)));
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
    private function main(
        InputInterface $input,
        OutputInterface $output,
        bool $showMarketData,
        bool $showNotional,
        bool $showAvailable,
        bool $showAvailableNotional
    ): void {
        $data = $this->getData($input, $output, $showMarketData, $showNotional, $showAvailable, $showAvailableNotional);
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
    private function getData(
        InputInterface $input,
        OutputInterface $output,
        bool $showMarketData,
        bool $showNotional,
        bool $showAvailable,
        bool $showAvailableNotional
    ): array {
        $data = [];
        try {
            $data[] = AccountNotionalBalance::getTable($output, $this->data, Config::getInstance());
            foreach ($this->getPairs($input, 'symbol') as $pair) {
                $data[] = TradeSpread::getTable($output, $this->data, $pair->getSymbol());
                if ($showMarketData) {
                    $data[] = MarketSpread::getTable($output, $this->data, $pair->getSymbol());
                }
            }
            $data[] = Balances::getTable($output, $this->data, $showNotional, $showAvailable, $showAvailableNotional);
            if ($input->getOption('price')) {
                $tblPrice = new Table($output);
                $tblPrice->setHeaders(['Asset Pair', 'Asking Price']);
                foreach ($this->getPairs($input, 'price') as $pair) {
                    $tblPrice->addRow([$pair->getSymbol(), $this->getPrice->getAsk($pair->getSymbol())]);
                }
                $data[] = $tblPrice;
            }
        } catch (\Kobens\Gemini\Exception $e) {
            $data[] = (new Table($output))->addRow([$e->getMessage()]);
        }
        return $data;
    }
}
