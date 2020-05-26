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
        $ask = $bid = null;
        foreach ($data->getOrdersData() as $order) {
            if ($order->symbol === $symbol) {
                if (
                    $order->side === 'sell'
                ) {
                    if ($ask === null || Compare::getResult($ask, $order->price) === Compare::RIGHT_LESS_THAN) {
                        $ask = $order->price;
                    }
                } elseif ($order->side === 'buy') {
                    if ($bid === null || Compare::getResult($bid, $order->price) === Compare::LEFT_LESS_THAN) {
                        $bid = $order->price;
                    }
                }
            }
        }
        $spread = Subtract::getResult($ask ?? '0', $bid ?? '0');
        $percent = $spread && $bid ? Divide::getResult($spread, $bid, 6) : '0';
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

        $sellSpread = $ask
            ? Subtract::getResult($ask, $data->getPriceResult($symbol)->getAsk())
            : 'N/A';
        $bidSpread = $bid
            ? Subtract::getResult($data->getPriceResult($symbol)->getBid(), $bid)
            : 'N/A';

        $scale = self::getScale($bid ?? '0', $ask ?? '0', $spread);
        $bid = (string) number_format((float) $bid, $scale, '.', '');
        $ask = (string) number_format((float) $ask, $scale, '.', '');
        $spread = (string) number_format((float) $spread, $scale);
        $length = self::getLength($bid, $ask, $spread);
        $bid = str_pad($bid, $length + 2, ' ', STR_PAD_LEFT);
        $ask = str_pad($ask, $length + 2, ' ', STR_PAD_LEFT);
        $spread = str_pad($spread, $length + 2, ' ', STR_PAD_LEFT);
        $percent = str_pad($percent, $length, ' ', STR_PAD_LEFT);


        $scale = self::getScale($sellSpread, $bidSpread);
        $sellSpread = (string) number_format((float) $sellSpread, $scale);
        $bidSpread = (string) number_format((float) $bidSpread, $scale);
        $sellSpread = str_pad($sellSpread, 13, ' ', STR_PAD_LEFT);
        $bidSpread = str_pad($bidSpread, 13, ' ', STR_PAD_LEFT);

        $table = new Table($output);
        $table
            ->setHeaders(
                [
                    new TableCell('<options=underscore>Trader Data</>', ['colspan' => 2]),
                    '<options=underscore>Market Spread</>',
                ]
            )
            ->setRows(
                [
                    ['Lowest Ask', "<fg=red>$ask</>", $sellSpread],
                    ['Highest Bid', "<fg=green>$bid</>", $bidSpread],
                    ['Spread', $spread],
                    ['Spread', "% $percent"],
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
