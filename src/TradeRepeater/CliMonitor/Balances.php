<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\CliMonitor;

use Kobens\Gemini\TradeRepeater\CliMonitor\Helper\DataInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface;
use Symfony\Component\Console\Helper\TableCell;
use Kobens\Math\BasicCalculator\Subtract;
use Kobens\Gemini\Exchange\Currency\Pair;

final class Balances
{
    private static int $strPad = 20;

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
        DataInterface $dataHelper,
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
        $extra = $dataHelper->getExtra();
        $balances = $dataHelper->getNotionalBalances();
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

        $symbols = [];
        $evenOdd = 0;
        foreach ($data as &$row) {
            $color = ++$evenOdd % 2 === 0 ? 'magenta' : 'cyan';
            $isUsd = $row[0] === 'USD';
            foreach ($row as $i => &$col) {
                if ($i !== 0) {
                    $padLength = $lengths[$i] < self::$strPad ? self::$strPad : $lengths[$i];
                    $col = str_pad($col, $padLength, ' ', STR_PAD_LEFT);
                }
                if ($i === 0 && $isUsd === false) {
                    $symbols[] = strtolower($col) . 'usd';
                }
                if ($isUsd) {
                    $col = '<fg=green>' . $col . '</>';
                } elseif ($i !== 0) {
                    $col = sprintf(
                        '<fg=%s>%s</>',
                        $color,
                        (string) $col
                    );
                }
            }
            if (($extra[strtolower($row[0]) . 'usd'] ?? null) !== null) {
                $row[] = sprintf(
                    '<fg=%s>%s</>',
                    $color,
                    (string) $extra[strtolower($row[0]) . 'usd']
                );
                unset($extra[strtolower($row[0]) . 'usd']);
            } else {
                $row[] = '';
            }
            if ($isUsd) {
                $row[] = '<fg=green>' . $dataHelper->getProfitsBucketValue('usd') . '</>';
//                 $row[] = end($row);
            } else {
                $row[] = sprintf(
                    '<fg=%s>%s</>',
                    $color,
                    (string) $dataHelper->getProfitsBucketValue(strtolower($row[0]))
                );
//                 $row[] = sprintf(
//                     '<fg=%s>%s</>',
//                     $color,
//                     (string) $dataHelper->getNotional(strtolower($row[0]) . 'usd', $rawEnd)
//                 );
            }
            if ($isUsd === false) {
                $row[0] = sprintf('<fg=%s>%s</>', $color, $row[0]);
            }
            $table->addRow($row);
        }
        foreach (array_keys($extra) as $symbol) {
            if (!in_array($symbol, ['usd_maker_deposit', 'total_usd_investment']) && !in_array($symbol, $symbols)) {
                $pair = Pair::getInstance($symbol);
                $row = [
                    strtoupper($pair->getBase()->getSymbol()),
                ];
                for ($i = 0, $j = $cols - 1; $i < $j; ++$i) {
                    $row[] = '';
                }
                $row[] = $extra[$symbol];
                $row[] = $dataHelper->getProfitsBucketValue($pair->getBase()->getSymbol());
                $row[] = $dataHelper->getNotional(strtolower($row[0]) . 'usd', end($row));
                $table->addRow($row);
            }
        }
        $table->addRow([new TableCell('', ['colspan' => $cols + 1])]);
        $table->addRow([new TableCell('Total Repeater USD Invested', ['colspan' => $cols]), $extra['total_usd_investment']]);
        $table->addRow([new TableCell('', ['colspan' => $cols + 1])]);
        $table->addRow([new TableCell('USD Maker Deposit', ['colspan' => $cols]), $extra['usd_maker_deposit']]);
        $table->addRow([
            new TableCell('USD Available Over Maker Deposit And Bucket(s)', ['colspan' => $cols]),
            str_pad(
                bcadd(
                    Subtract::getResult(
                        Subtract::getResult(
                            $dataHelper->getNotionalBalances()['USD']->getAvailable(),
                            trim($extra['usd_maker_deposit'])
                        ),
                        $dataHelper->getProfitsBucketValue('usd')
                    ),
                    '0',
                    strlen(explode('.', $extra['usd_maker_deposit'])[1] ?? '')
                ),
                strlen($extra['usd_maker_deposit']),
                ' ',
                STR_PAD_LEFT
            )
        ]);
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
            $headers[] = str_pad('Amount', self::$strPad, ' ', STR_PAD_LEFT);
        }
        if ($amountNotional) {
            $headers[] = str_pad('Amount Notional', self::$strPad, ' ', STR_PAD_LEFT);
        }
        if ($available) {
            $headers[] = str_pad('Available', self::$strPad, ' ', STR_PAD_LEFT);
        }
        if ($availableNotional) {
            $headers[] = str_pad('Available Notional', self::$strPad, ' ', STR_PAD_LEFT);
        }
        $headers[] = 'Repeater Invested USD';
        $headers[] = 'Profit Bucket';
//         $headers[] = 'Profit Bucket Notional';
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
