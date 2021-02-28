<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\CliMonitor;

use Kobens\Core\Config;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\Exchange\Currency\Pair as CurrencyPair;
use Kobens\Gemini\TradeRepeater\CliMonitor\MarketSpread;
use Kobens\Gemini\TradeRepeater\CliMonitor\TradeSpread;
use Kobens\Gemini\TradeRepeater\CliMonitor\Helper\Data;
use Kobens\Http\Exception\Status\ServerErrorException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

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

    protected function configure(): void
    {
        $this->setDescription('Monitoring output on trades and the market');
        $this->addArgument('symbol', InputArgument::OPTIONAL, 'Comma separated trading pair(s) to show trade-repeater data for', 'btcusd');
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
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
                $exitCode = 1;
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
        return $exitCode;
    }

    /**
     * @return \Kobens\Exchange\PairInterface[]
     */
    private function getPairs(InputInterface $input): array
    {
        $pairs = [];
        if ($input->getArgument('symbol') !== null) {
            foreach (explode(',', $input->getArgument('symbol')) as $symbol) {
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
        try {
            $data = $this->getData($input, $output);
        } catch (ServerErrorException $e) {
            $data = [(new Table($output))->setRows([['Server Error at Exchange']])];
        }

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
     * @param \Kobens\Exchange\PairInterface[] $pairs
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
