<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\CliMonitor;

use Kobens\Gemini\TradeRepeater\CliMonitor\Helper\DataInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface;
use Symfony\Component\Console\Helper\TableCell;

final class Balances
{
    /**
     * @param OutputInterface $output
     * @param DataInterface $data
     * @param bool $amountNotional
     * @param bool $amountAvailable
     * @param bool $amountAvailableNotional
     * @return Table
     */
    public static function getTable(
        OutputInterface $output,
        DataInterface $data,
        bool $amount = false,
        bool $amountNotional = false,
        bool $amountAvailable = false,
        bool $amountAvailableNotional = false
    ): Table {
        $cols = 1;
        if ($amount) {
            $cols++;
        }
        if ($amountNotional) {
            $cols++;
        }
        if ($amountAvailable) {
            $cols++;
        }
        if ($amountAvailableNotional) {
            $cols++;
        }
        $table = new Table($output);
        $extra = $data->getExtra();
        $balances = $data->getNotionalBalances();
        $table->setHeaderTitle('Balances');
        $table->setHeaders(self::getHeaders($amount, $amountNotional, $amountAvailable, $amountAvailableNotional));

        $data = [];
        $lengths = [];
        foreach ($balances as $balance) {
            $row = self::getRow($balance, $amount, $amountNotional, $amountAvailable, $amountAvailableNotional);
            foreach ($row as $i => &$val) {
                if ($i === 0) {
                    continue;
                }
                $val = bcadd($val, '0', 8);
                $length = strlen($val);
                if ($length > ($lengths[$i] ?? 0)) {
                    $lengths[$i] = $i;
                }
            }
            $data[] = $row;
        }

        foreach ($data as &$row) {
            $isUsd = $row[0] === 'USD';
            foreach ($row as $i => &$col) {
                if ($i !== 0) {
                    $padLength = $lengths[$i] < 18 ? 18 : $lengths[$i];
                    $col = str_pad($col, $padLength, ' ', STR_PAD_LEFT);
                }
                if ($isUsd) {
                    $col = '<fg=green>' . $col . '</>';
                }
            }
            if (($extra[strtolower($row[0]) . 'usd'] ?? null) !== null) {
                $row[] = $extra[strtolower($row[0]) . 'usd'];
            }
            $table->addRow($row);
        }
        $table->addRow([new TableCell('', ['colspan' => $cols + 1])]);
        $table->addRow([new TableCell('USD Maker Deposit', ['colspan' => $cols]), $extra['usd_maker_deposit']]);
        $table->addRow([new TableCell('Total Repeater USD Invested', ['colspan' => $cols]), $extra['total_usd_investment']]);
        return $table;
    }

    /**
     * @param bool $amount
     * @param bool $amountNotional
     * @param bool $available
     * @param bool $availableNotional
     * @return array
     */
    private static function getHeaders(bool $amount = false, bool $amountNotional = false, bool $available = false, bool $availableNotional = false): array
    {
        $headers = [
            'Currency',
        ];
        if ($amount) {
            $headers[] = 'Amount';
        }
        if ($amountNotional) {
            $headers[] = 'Amount Notional';
        }
        if ($available) {
            $headers[] = 'Available';
        }
        if ($availableNotional) {
            $headers[] = 'Available Notional';
        }
        $headers[] = 'Repeater Invested USD';
        return $headers;
    }

    /**
     * @param BalanceInterface $balance
     * @param bool $amount
     * @param bool $amountNotional
     * @param bool $amountAvailable
     * @param bool $amountAvailableNotional
     * @return array
     */
    private static function getRow(
        BalanceInterface $balance,
        bool $amount = false,
        bool $amountNotional = false,
        bool $amountAvailable = false,
        bool $amountAvailableNotional = false
    ): array {
        $data = [
            $balance->getCurrency(),
        ];
        if ($amount) {
            $data[] = $balance->getAmount();
        }
        if ($amountNotional) {
            $data[] = $balance->getAmountNotional();
        }
        if ($amountAvailable) {
            $data[] = $balance->getAvailable();
        }
        if ($amountAvailableNotional) {
            $data[] = $balance->getAvailableNotional();
        }
        return $data;
    }
}
