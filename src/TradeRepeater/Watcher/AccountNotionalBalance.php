<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Watcher;

use Kobens\Core\Config;
use Kobens\Gemini\TradeRepeater\Watcher\Helper\DataInterface;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

final class AccountNotionalBalance
{
    public static function getTable(OutputInterface $output, DataInterface $data, Config $config): Table
    {
        $balances = $data->getNotionalBalances();
        $amount = '0';
        foreach ($balances as $balance) {
            $amount = Add::getResult($amount, $balance->getAmountNotional());
        }
        $investedUsd = (string) $config->get('storage')->invested_usd;
        $diff = Subtract::getResult($amount, $investedUsd);
        if (Compare::getResult($diff, '0') == Compare::LEFT_GREATER_THAN) {
            $diffFormat = "green";
        } else {
            $diffFormat = "red";
        }
        $diff = self::formatVal($diff);
        $investedUsd = self::formatVal($investedUsd);
        $amount = self::formatVal($amount);
        $table = new Table($output);
        $table
            ->setRows([
                ['Account Notional Balance USD:', "$ <fg=green>$amount</>"],
                ['USD Invested', "$ $investedUsd"],
                ['Difference', "$ <fg=$diffFormat>$diff</>"],
            ]);
        return $table;
    }

    private static function formatVal(string $val): string
    {
        $val = (string) \number_format((float) $val, 2);
        $val = str_pad($val, 10, ' ', STR_PAD_LEFT);
        return $val;
    }
}
