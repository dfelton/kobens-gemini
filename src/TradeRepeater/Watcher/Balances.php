<?php

namespace Kobens\Gemini\TradeRepeater\Watcher;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances;
use Kobens\Gemini\TradeRepeater\Watcher\Helper\DataInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

final class Balances
{
    public static function getTable(OutputInterface $output, DataInterface $data): Table
    {
        $table = new Table($output);
        $balances = $data->getNotionalBalances();
        $table->setHeaders([
            'Currency',
            'Amount',
            'Amount Available',
            'Amount Notional USD',
        ]);
        foreach ($balances as $balance) {
            $table->addRow([
                $balance->getCurrency(),
                $balance->getAmount(),
                $balance->getAvailable(),
                $balance->getAmountNotional(),
            ]);
        }
        return $table;
    }
}