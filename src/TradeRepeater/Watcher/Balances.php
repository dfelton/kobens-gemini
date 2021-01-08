<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Watcher;

use Kobens\Gemini\TradeRepeater\Watcher\Helper\DataInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface;

final class Balances
{
    public static function getTable(
        OutputInterface $output,
        DataInterface $data,
        bool $showNotional = false,
        bool $showAvailable = false,
        bool $showAvailableNotional = false
    ): Table {
        $table = new Table($output);
        $balances = $data->getNotionalBalances();
        $table->setHeaderTitle('Balances');
        $table->setHeaders(self::getHeaders($showNotional, $showAvailable, $showAvailableNotional));
        foreach ($balances as $balance) {
            $table->addRow(self::getRow($balance, $showNotional, $showAvailable, $showAvailableNotional));
        }
        return $table;
    }

    private static function getHeaders(bool $showNotional = false, bool $showAvailable = false, bool $showAvailableNotional = false): array
    {
        $headers = [
            'Currency',
            'Amount',
        ];
        if ($showNotional) {
            $headers[] = 'Amount Notional';
        }
        if ($showAvailable) {
            $headers[] = 'Available';
        }
        if ($showAvailableNotional) {
            $headers[] = 'Available Notional';
        }
        return $headers;
    }

    private static function getRow(
        BalanceInterface $balance,
        bool $showNotional = false,
        bool $showAvailable = false,
        bool $showAvailableNotional = false
    ): array {
        $data = [
            $balance->getCurrency(),
            $balance->getAmount(),
        ];
        if ($showNotional) {
            $data[] = $balance->getAmountNotional();
        }
        if ($showAvailable) {
            $data[] = $balance->getAvailable();
        }
        if ($showAvailableNotional) {
            $data[] = $balance->getAvailableNotional();
        }
        return $data;
    }
}
