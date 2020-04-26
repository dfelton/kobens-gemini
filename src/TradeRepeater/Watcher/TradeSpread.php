<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Watcher;

use Kobens\Gemini\TradeRepeater\Watcher\Helper\DataInterface;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Divide;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\OutputInterface;

final class TradeSpread implements TableReportInterface
{
    public static function getTable(OutputInterface $output, DataInterface $data, string $symbol): Table
    {
        $ask = $bid = null;
        foreach ($data->getOrdersData() as $order) {
            if ($order->symbol === $symbol) {
                if ($order->side === 'sell'
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

        $sellSpread = Subtract::getResult($ask, $data->getPriceResult($symbol)->getAsk());
        $bidSpread = Subtract::getResult($data->getPriceResult($symbol)->getBid(), $bid);

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
                    ['Lowest Ask', "<fg=red>$ask</>", str_pad($sellSpread, 13, ' ', STR_PAD_LEFT)],
                    ['Highest Bid', "<fg=green>$bid</>", str_pad($bidSpread, 13, ' ', STR_PAD_LEFT)],
                    ['Spread', $spread],
                    ['Spread', "$percent %"],
                ]
            );
        return $table;
    }
}
