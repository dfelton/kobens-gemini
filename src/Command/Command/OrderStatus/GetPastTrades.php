<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetPastTradesInterface;
use Symfony\Component\Console\Command\Command;
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
        $this->addOption('symbol',    's', InputOption::VALUE_OPTIONAL, 'Trading Symbol', 'btcusd');
        $this->addOption('limit',     'l', InputOption::VALUE_OPTIONAL, 'Trades to fetch (limit 500)', 10);
        $this->addOption('timestamp', 't', InputOption::VALUE_OPTIONAL, 'Timestamp to fetch time since (default is most recent trades).');
        $this->addOption('raw',       'r', InputOption::VALUE_OPTIONAL, 'Whether or not to output raw response data or not', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symbol = $input->getOption('symbol');
        $data = $this->trades->getTrades($symbol, $input->getOption('timestamp'), $input->getOption('limit'));

        if ($input->getOption('raw') !== false) {
            $output->writeln(\json_encode($data));
        } else {
            foreach ($data as $i => $trade) {
                if ($i === 0 || $i % 50 === 0) {
                    $this->outputHeaders($output, $symbol);
                }
                $this->outputTrade($output, $trade);
            }
        }
    }

    private function outputTrade(OutputInterface $output, \stdClass $trade): void
    {
        $output->writeln(\sprintf(
            "%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s",
            $trade->price. (\strlen($trade->price) < 8 ? "\t" : ''),
            $this->getFormattedAmount($trade->amount),
            $trade->timestampms,
            $this->getFormattedDate($trade->timestampms),
            $trade->type === 'Buy' ? '<fg=green>Buy</>' : '<fg=red>Sell</>',
            $trade->aggressor ? '<fg=red>Taker</>' : '<fg=green>Maker</>',
            $trade->fee_currency,
            $trade->fee_amount . (\strlen($trade->fee_amount) <= 7 ? "\t":''),
            $trade->tid,
            $trade->order_id
        ));
    }

    private function outputHeaders(OutputInterface $output, string $symbol): void
    {
        $output->write(\sprintf(
            "\n%s\t\t%s\t\t%s\t%s       %s\t\t%s\t%s\t%s\t%s\t%s\t%s\n",
            '<options=underscore>Price</>',
            '<options=underscore>Amount</>',
            '<options=underscore>TimestampMS</>',
            '<options=underscore>Date</>',
            '<options=underscore>Time</>',
            '<options=underscore>Side</>',
            '<options=underscore>Type</>',
            '<options=underscore>Fee</>',
            '<options=underscore>Fee Amount</>',
            '<options=underscore>Transaction ID</>',
            '<options=underscore>Order ID</>'
        ));
    }

    private function getFormattedDate(int $timestampms): string
    {
        $date = $timestampms/1000;
        $date = \explode('.', $date);
        return \date('Y-m-d H:i:s', $date[0]) . (isset($date[1]) ? '.'.$date[1] : '');
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
