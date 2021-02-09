<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\CliMonitor;

use Kobens\Gemini\TradeRepeater\CliMonitor\Helper\DataInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface;

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
        $table = new Table($output);
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
            if ($row[0] === 'USD') {
            } else {
                $table->addRow($row);
            }
        }
        return $table;
    }

    /**
     * @param bool $amount
     * @param bool $amountNotional
     * @param bool $available
     * @param bool $availableNotional
     * @throws \InvalidArgumentException
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
        if (count($headers) === 1) {
            throw new \InvalidArgumentException('Must show at least one column (amount, amount notional, available, or available notional).');
        }
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
            $balance->getAmount(),
        ];
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
