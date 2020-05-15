<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\Config;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Watcher\AccountNotionalBalance;
use Kobens\Gemini\TradeRepeater\Watcher\MarketSpread;
use Kobens\Gemini\TradeRepeater\Watcher\TradeSpread;
use Kobens\Gemini\TradeRepeater\Watcher\Helper\Data;
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

    static protected $defaultName = 'trade-repeater:watcher';

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
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Trading Pair Symbol', 'btcusd');
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
        $symbol = Pair::getInstance($input->getOption('symbol'))->getSymbol();
        $loop = $input->getOption('loop');
        $loop = $loop === false ? false : true;
        do {
            try {
                $this->main($output, $symbol);
                $this->data->reset();
                if ($loop) {
                    $this->sleeper->sleep($sleep, static function(){});
                }
            } catch (\Throwable $e) {
                $loop = false;
                do {
                    $output->writeln([
                        'Code: ' . $e->getCode(),
                        'Class: ' . \get_class($e),
                        'Message: ' . $e->getMessage(),
                        "Strace:\n" . $e->getTraceAsString(),
                    ]);
                    $e = $e->getPrevious();
                    if ($e instanceof \Throwable) {
                        $output->writeln("\nPrevious:");
                    }
                } while ($e instanceof \Throwable);
            }
        } while ($loop);
    }

    private function main(OutputInterface $output, string $symbol): void
    {
        $data = $this->getData($output, $symbol);
        system('clear');
        $output->write(PHP_EOL);

        (new Table($output))->setRows(
            [
                ['Date / Time:', (new \DateTime())->format('Y-m-d H:i:s')],
                ['Symbol', "<fg=yellow>$symbol</>"],
            ]
        )->render();
        $output->write(PHP_EOL);
        foreach ($data as $table) {
            $table->render();
            $output->write(PHP_EOL);
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
     * @return Table[]
     */
    private function getData(OutputInterface $output, string $symbol): array
    {
        $data = [];
        try {
            $data[] = AccountNotionalBalance::getTable($output, $this->data, Config::getInstance());
            $data[] = TradeSpread::getTable($output, $this->data, $symbol);
            $data[] = MarketSpread::getTable($output, $this->data, $symbol);
        } catch (\Kobens\Gemini\Exception $e) {
            $data[] = (new Table($output))->addRow([$e->getMessage()]);
        }
        return $data;
    }

}
