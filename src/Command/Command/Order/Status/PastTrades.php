<?php

namespace Kobens\Gemini\Command\Command\Order\Status;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

final class PastTrades extends Command
{
    protected static $defaultName = 'kobens:gemini:order:past-trades';

    protected function configure()
    {
        $this->setDescription('Fetches trade data since a given timestamp');
        $this->addArgument('symbol', InputArgument::OPTIONAL, 'Symbol to fetch trades for', 'btcusd');
        $this->addArgument('limit', InputArgument::OPTIONAL, 'Trades to fetch (limit 500)', 50);
        $this->addArgument('timestamp', InputArgument::OPTIONAL, 'Timestamp to fetch time since (default is now).', -1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symbol = $input->getArgument('symbol');
        $timestamp = $input->getArgument('timestamp');
        $limit = $input->getArgument('limit');

        $pastTrades = new \Kobens\Gemini\Api\Rest\Request\Order\Status\PastTrades($symbol, $timestamp, $limit);;

        $response = $pastTrades->getResponse();
        $trades = \json_decode($response['body'], true);

        echo $response['body'];exit;

        $this->outputHeaders($output, $symbol);
        foreach ($trades as $trade) {
            $date = $trade['timestampms']/1000;
            $date = explode('.', $date);
            $formattedDate = \date('Y-m-d H:i:s', $date[0]) . (isset($date[1]) ? '.'.$date[1] : '');

            $output->writeln(\sprintf(
                "%s\t%s\t\t%s\t%s\t%s\t%s\t%s\t%s\t%s",
                $trade['price']. (\strlen($trade['price']) < 8 ? "\t" : ''),
                $trade['amount'],
                $formattedDate,
                $trade['type'] === 'Buy' ? '<fg=green>Buy</>' : '<fg=red>Sell</>',
                $trade['aggressor'] ? '<fg=red>Taker</>' : '<fg=green>Maker</>',
                $trade['fee_currency'],
                $trade['fee_amount'] . (\strlen($trade['fee_amount']) <= 8 ? "\t":''),
                $trade['tid'],
                $trade['order_id']
            ));
        }

    }

    private function outputHeaders(OutputInterface $output, string $symbol): void
    {
        $output->writeln("Trading history for $symbol:\n");
        $output->write(\sprintf(
            "%s\t\t%s\t\t%s       %s\t\t%s\t%s\t%s\t%s\t%s\t%s\n",
            '<options=underscore>Price</>',
            '<options=underscore>Amount</>',
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

}
