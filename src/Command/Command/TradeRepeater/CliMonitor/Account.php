<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\CliMonitor;

use Kobens\Core\Config;
use Kobens\Core\SleeperInterface;
use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Watcher\AccountNotionalBalance;
use Kobens\Gemini\TradeRepeater\Watcher\Balances;
use Kobens\Gemini\TradeRepeater\Watcher\Helper\Data;
use Kobens\Gemini\TradeRepeater\Watcher\Profits;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class Account extends Command
{
    private const KILL_FILE = '/var/kill_monitor';

    private const REFRESH_DEFAULT = 10;
    private const REFRESH_MIN = 5;
    private const REFRESH_MAX = 3600;

    protected static $defaultName = 'repeater:monitor:account';

    private SleeperInterface $sleeper;

    private Data $data;

    private Profits $profits;

    public function __construct(
        Data $data,
        SleeperInterface $sleeperInterface,
        Profits $profits
    ) {
        $this->data = $data;
        $this->sleeper = $sleeperInterface;
        $this->profits = $profits;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Monitoring: Account Summary');
        $this->addOption('amount', null, InputOption::VALUE_OPTIONAL, 'Show balance(s) "Amount".', false);
        $this->addOption('amount-notional', null, InputOption::VALUE_OPTIONAL, 'Show balance(s) "Notional Amount".', false);
        $this->addOption('available', null, InputOption::VALUE_OPTIONAL, 'Show balance(s) "Amount Available".', false);
        $this->addOption('available-notional', null, InputOption::VALUE_OPTIONAL, 'Show balance(s) "Amount Available Notional"', false);
        $this->addOption('disable-loop', 'd', InputOption::VALUE_OPTIONAL, 'Disable continuous output', false);
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
        $amount = $input->getOption('amount') !== false;
        $amountNotional = $input->getOption('amount-notional') !== false;
        $amountAvailable = $input->getOption('available') !== false;
        $amountAvailableNotional = $input->getOption('available-notional') !== false;
        $loop = $input->getOption('disable-loop') === false;
        do {
            try {
                $this->main(
                    $input,
                    $output,
                    $amount,
                    $amountNotional,
                    $amountAvailable,
                    $amountAvailableNotional
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
                $pairs[] = Pair::getInstance(strtolower(trim($symbol)));
            }
        }
        return $pairs;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param bool $showMarketData
     * @param bool $amountNotional
     * @param bool $amountAvailable
     * @param bool $amountAvailableNotional
     */
    private function main(
        InputInterface $input,
        OutputInterface $output,
        bool $amount,
        bool $amountNotional,
        bool $amountAvailable,
        bool $amountAvailableNotional
    ): void {
        $data = $this->getData($input, $output, $amount, $amountNotional, $amountAvailable, $amountAvailableNotional);
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param bool $amount
     * @param bool $amountNotional
     * @param bool $amountAvailable
     * @param bool $amountAvailableNotional
     * @return Table[]
     */
    private function getData(
        InputInterface $input,
        OutputInterface $output,
        bool $amount,
        bool $amountNotional,
        bool $amountAvailable,
        bool $amountAvailableNotional
    ): array {
        $data = [];
        try {
            $data[] = AccountNotionalBalance::getTable($output, $this->data, Config::getInstance());
            $data[] = Balances::getTable($output, $this->data, $amount, $amountNotional, $amountAvailable, $amountAvailableNotional);
            $data[] = $this->profits->get($output);
        } catch (\Kobens\Gemini\Exception $e) {
            $data[] = (new Table($output))->addRow([$e->getMessage()]);
        }
        return $data;
    }
}
