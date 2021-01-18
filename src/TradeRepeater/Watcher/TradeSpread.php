<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Watcher;

use Kobens\Gemini\TradeRepeater\Watcher\Helper\DataInterface;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Divide;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\OutputInterface;

final class TradeSpread
{
    public static function getTable(OutputInterface $output, DataInterface $data, string $symbol): Table
    {
        $askMin = $askMax = $bidMin = $bidMax = '';
        $sellCount = 0;
        $buyCount = 0;
        foreach ($data->getOrdersData() as $order) {
            if (
                $order->symbol === $symbol &&
                ($order->client_order_id ?? null) &&
                strpos($order->client_order_id, 'repeater_') === 0
            ) {
                if (
                    $order->side === 'sell'
                ) {
                    ++$sellCount;
                    if ($askMax === '' || $askMin === '') {
                        $askMax = $order->price;
                        $askMin = $order->price;
                        continue;
                    }
                    if (Compare::getResult($askMin, $order->price) === Compare::LEFT_GREATER_THAN) {
                        $askMin = $order->price;
                    }
                    if (Compare::getResult($askMax, $order->price) === Compare::LEFT_LESS_THAN) {
                        $askMax = $order->price;
                    }
                } elseif ($order->side === 'buy') {
                    ++$buyCount;
                    if ($bidMin === '' || $bidMax === '') {
                        $bidMax = $bidMin = $order->price;
                        continue;
                    }
                    if (Compare::getResult($bidMax, $order->price) === Compare::LEFT_LESS_THAN) {
                        $bidMax = $order->price;
                    }
                    if (Compare::getResult($bidMin, $order->price) === Compare::LEFT_GREATER_THAN) {
                        $bidMin = $order->price;
                    }
                }
            }
        }
        $spread = Subtract::getResult($askMin ?? '0', $bidMax ?? '0');
        $percent = $spread && $bidMax ? Divide::getResult($spread, $bidMax, 6) : '0';
        if (\strpos($percent, '.') !== false) {
            $percent = \explode('.', $percent);
            $percent[1] = \str_pad($percent[1], 6, '0', STR_PAD_RIGHT);
            $percent[0] = $percent[0] . $percent[1][0] . $percent[1][1];
            $percent[0] = \ltrim($percent[0], '0') ?: '0';
            $percent = $percent[0] . '.' . \substr($percent[1], 2);
            $percent = \rtrim(\rtrim($percent, '0'), '.');
        } else {
            $percent = $percent . '00';
        }

        $sellMinSpread = $askMin
            ? Subtract::getResult($askMin, $data->getPriceResult($symbol)->getAsk())
            : 'N/A';
        $sellMaxSpread = $askMax
            ? Subtract::getResult($askMax, $data->getPriceResult($symbol)->getAsk())
            : 'N/A';
        $bidMaxSpread = $bidMax
            ? Subtract::getResult($data->getPriceResult($symbol)->getBid(), $bidMax)
            : 'N/A';
        $bidMinSpread = $bidMin
            ? Subtract::getResult($data->getPriceResult($symbol)->getBid(), $bidMin)
            : 'N/A';

        $scale = self::getScale(
            $bidMax ?? '0',
            $bidMin ?? '0',
            $askMin ?? '0',
            $askMax ?? '0',
            $spread
        );
        $bidMax = (string) number_format((float) $bidMax, $scale, '.', '');
        $bidMin = (string) number_format((float) $bidMin, $scale, '.', '');
        $askMin = (string) number_format((float) $askMin, $scale, '.', '');
        $askMax = (string) number_format((float) $askMax, $scale, '.', '');
        $spread = (string) number_format((float) $spread, $scale);
        $length = self::getLength($bidMax, $bidMax, $askMin, $askMax, $spread);


        $bidMax = str_pad($bidMax, $length + 2, ' ', STR_PAD_LEFT);
        $bidMin = str_pad($bidMin, $length + 2, ' ', STR_PAD_LEFT);
        $askMax = str_pad($askMax, $length + 2, ' ', STR_PAD_LEFT);
        $askMin = str_pad($askMin, $length + 2, ' ', STR_PAD_LEFT);
        $spread = str_pad($spread, $length + 2, ' ', STR_PAD_LEFT);
        $percent = str_pad($percent, $length, ' ', STR_PAD_LEFT);
        $buyCount = str_pad((string) $buyCount, $length + 2, ' ', STR_PAD_LEFT);
        $sellCount = str_pad((string) $sellCount, $length + 2, ' ', STR_PAD_LEFT);


        $scale = self::getScale($sellMinSpread, $bidMaxSpread, $sellMaxSpread, $bidMinSpread);
        $sellMinSpread = (string) number_format((float) $sellMinSpread, $scale);
        $sellMaxSpread = (string) number_format((float) $sellMaxSpread, $scale);
        $bidMaxSpread = (string) number_format((float) $bidMaxSpread, $scale);
        $bidMinSpread = (string) number_format((float) $bidMinSpread, $scale);

        $sellMaxSpread = str_pad($sellMaxSpread, 13, ' ', STR_PAD_LEFT);
        $sellMinSpread = str_pad($sellMinSpread, 13, ' ', STR_PAD_LEFT);
        $bidMinSpread = str_pad($bidMinSpread, 13, ' ', STR_PAD_LEFT);
        $bidMaxSpread = str_pad($bidMaxSpread, 13, ' ', STR_PAD_LEFT);

        $table = new Table($output);
        $table
            ->setHeaders(
                [
                    new TableCell(
                        sprintf('<options=underscore>Trader Data</> %s', strtoupper($symbol)),
                        ['colspan' => 2]
                    ),
                    '<options=underscore>Market Spread</>',
                ]
            )
            ->setRows(
                [
                    ['Highest Ask', "<fg=red>$askMax</>", $sellMaxSpread],
                    ['Lowest Ask', "<fg=red>$askMin</>", $sellMinSpread],
                    ['Highest Bid', "<fg=green>$bidMax</>", $bidMaxSpread],
                    ['Lowest Bid', "<fg=green>$bidMin</>", $bidMinSpread],
                    ['Spread', $spread],
                    ['Spread', "% $percent"],
                    ['Sell Count', "<fg=red>$sellCount</>"],
                    ['BuyCount', "<fg=green>$buyCount</>"],
                ]
            );
        return $table;
    }

    private static function getScale(string ...$values): int
    {
        $scale = 0;
        $prev = $values[0] ?? '0';
        foreach ($values as $value) {
            $newScale = Add::getScale($prev, $value);
            if ($newScale > $scale) {
                $scale = $newScale;
            }
            $prev = $value;
        }
        return $scale;
    }

    private static function getLength(string ...$values): int
    {
        $length = 0;
        foreach ($values as $value) {
            $newLength = strlen($value);
            if ($newLength > $length) {
                $length = $newLength;
            }
        }
        return $length;
    }
}
