<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\CliMonitor;

use Kobens\Core\Config;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\CliMonitor\AccountNotionalBalance;
use Kobens\Gemini\TradeRepeater\CliMonitor\Balances;
use Kobens\Gemini\TradeRepeater\CliMonitor\Profits;
use Kobens\Gemini\TradeRepeater\CliMonitor\Helper\Data;
use Kobens\Http\Exception\Status\ServerErrorException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;

final class Account extends Command
{
    private const KILL_FILE = '/var/kill_monitor';

    private const REFRESH_DEFAULT = 10;
    private const REFRESH_MIN = 5;
    private const REFRESH_MAX = 3600;

    protected static $defaultName = 'repeater:account';

    private SleeperInterface $sleeper;

    private Data $data;

    private Profits $profits;

    private TableGatewayInterface $tblTradeRepeater;

    public function __construct(
        Data $data,
        SleeperInterface $sleeperInterface,
        Profits $profits,
        Adapter $adapter
    ) {
        $this->data = $data;
        $this->sleeper = $sleeperInterface;
        $this->profits = $profits;
        $this->tblTradeRepeater = new TableGateway('trade_repeater', $adapter);
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Monitoring: Account Summary');
        $this->addOption('amount', null, InputOption::VALUE_OPTIONAL, 'Show balance(s) "Amount".', true);
        $this->addOption('amount-notional', null, InputOption::VALUE_OPTIONAL, 'Show balance(s) "Notional Amount".', true);
        $this->addOption('available', null, InputOption::VALUE_OPTIONAL, 'Show balance(s) "Amount Available".', true);
        $this->addOption('available-notional', null, InputOption::VALUE_OPTIONAL, 'Show balance(s) "Amount Available Notional"', true);
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $sleep = $this->getRefreshRate($input);
        $args = $this->getArgs($input);
        $loop = $input->getOption('disable-loop') === false;
        do {
            try {
                $this->main(
                    $input,
                    $output,
                    $args
                );
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

    private function getArgs(InputInterface $input): array
    {
        return [
            $input->getOption('amount') !== false,
            $input->getOption('amount-notional') !== false,
            $input->getOption('available') !== false,
            $input->getOption('available-notional') !== false,
        ];
    }

    /**
     * @return \Kobens\Exchange\PairInterface[]
     */
    private function getPairs(InputInterface $input, string $option): array
    {
        $pairs = [];
        if ($input->getOption($option) !== null) {
            foreach (explode(',', $input->getOption($option)) as $symbol) {
                $symbol = strtolower(trim($symbol));
                $pairs[$symbol] = Pair::getInstance($symbol);
            }
        }
        ksort($pairs);
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
        array $args
    ): void {
        try {
            $data = $this->getData($input, $output, ...$args);
        } catch (ServerErrorException $e) {
            $data = [(new Table($output))->setRows([['Server Error at Exchange']])];
        }
        $output->write("\e[H\e[J");
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
            if ($amount || $amountNotional || $amountAvailable || $amountAvailableNotional) {
                $data[] = Balances::getTable($output, $this->data, $amount, $amountNotional, $amountAvailable, $amountAvailableNotional);
            }
            $data[] = $this->profits->get($output);
        } catch (\Kobens\Gemini\Exception $e) {
            $data[] = (new Table($output))->addRow([$e->getMessage()]);
        }
        return $data;
    }
}
