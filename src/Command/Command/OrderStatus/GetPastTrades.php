<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetPastTradesInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableRows;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GetPastTrades extends Command
{
    protected static $defaultName = 'order-status:get-trades';

    private GetPastTradesInterface $trades;

    public function __construct(
        GetPastTradesInterface $getPastTradesInterface
    ) {
        $this->trades = $getPastTradesInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fetches trade data since a given timestamp');
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Trading Symbol', 'btcusd');
        $this->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Trades to fetch (limit 500)', 10);
        $this->addOption('timestamp', 't', InputOption::VALUE_OPTIONAL, 'Timestamp (supports ms timestamp) to fetch time since (default is most recent trades).');
        $this->addOption('raw', 'r', InputOption::VALUE_OPTIONAL, 'Whether or not to output raw response data or not', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symbol = $input->getOption('symbol');
        $timestamp = $input->getOption('timestamp');
        $timestamp = $timestamp === null ? null : (int) $timestamp;
        $limit = $input->getOption('limit');
        $limit = $limit === null ? null : (int) $limit;
        $data = $this->trades->getTrades($symbol, $timestamp, $limit);

        if ($input->getOption('raw') !== false) {
            $output->writeln(\json_encode($data));
        } else {
            $table = null;
            foreach ($data as $i => $trade) {
                if ($i === 0 || $i % 50 === 0) {
                    if ($table instanceof Table) {
                        $table->render();
                    }
                    $table = $this->getTable($output);
                }
                $table->addRow($this->getRow($trade));
            }
            if ($table instanceof Table) {
                $table->render();
            }
        }
    }

    private function getTable(OutputInterface $output): Table
    {
        $table = new Table($output);
        $table->setHeaders([
            'Price',
            'Amount',
            'TimestampMS',
            'Date',
            'Side',
            'Type',
            'Fee',
            'Fee Amount',
            'Transaction ID',
            'Order ID',
            'Client Order ID',
        ]);
        return $table;
    }

    private function getRow(\stdClass $trade): array
    {
        return [
            $trade->price,
            $this->getFormattedAmount($trade->amount),
            $trade->timestampms,
            $this->getFormattedDate($trade->timestampms),
            $trade->type === 'Buy' ? '<fg=green>Buy</>' : '<fg=red>Sell</>',
            $trade->aggressor ? '<fg=red>Taker</>' : '<fg=green>Maker</>',
            $trade->fee_currency,
            $trade->fee_amount,
            $trade->tid,
            $trade->order_id,
            $trade->client_order_id,
        ];
    }

    private function getFormattedDate(int $timestampms): string
    {
        $date = $timestampms / 1000;
        $date = \explode('.', (string) $date);
        return \date('Y-m-d H:i:s', (int) $date[0]) . (isset($date[1]) ? '.' . $date[1] : '');
    }

    private function getFormattedAmount(string $amount): string
    {
        if (\strpos($amount, '.') !== false) {
            $amount = \explode('.', $amount);
            $amount[1] = \str_pad($amount[1], 8, '0', STR_PAD_RIGHT);
            $amount = \implode('.', $amount);
        } else {
            $amount .= $amount.'.00000000';
        }
        return $amount;
    }
}
