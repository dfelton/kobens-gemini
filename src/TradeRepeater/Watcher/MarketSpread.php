<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Watcher;

use Kobens\Gemini\TradeRepeater\Watcher\Helper\DataInterface;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\OutputInterface;

final class MarketSpread
{
    public static function getTable(OutputInterface $output, DataInterface $data, string $symbol): Table
    {
        $result = $data->getPriceResult($symbol);
        $spread = Subtract::getResult($result->getAsk(), $result->getBid());
        $table = new Table($output);
        $scale = self::getScale($result->getAsk(), $result->getBid(), $spread);
        $ask = (string) \number_format((float) $result->getAsk(), $scale);
        $bid = (string) \number_format((float) $result->getBid(), $scale);
        $spread = (string) \number_format((float) $spread, $scale);
        $length = self::getLength($ask, $bid, $spread);
        $ask = str_pad($ask, $length, ' ', STR_PAD_LEFT);
        $bid = str_pad($bid, $length, ' ', STR_PAD_LEFT);
        $spread = str_pad($spread, $length, ' ', STR_PAD_LEFT);

        $table
            ->setHeaders(
                [
                    new TableCell('<options=underscore>Market Data</>', ['colspan' => 2])
                ]
            )
            ->setRows(
                [
                    ['Current Ask', "<fg=red>{$ask}</>"],
                    ['Current Bid', "<fg=green>{$bid}</>"],
                    ['Spread', $spread],
                ]
            );
        return $table;
    }

    private static function getLength(string $ask, string $bid, string $spread): int
    {
        $length = \strlen($ask);
        if ($length < \strlen($bid)) {
            $length = strlen($bid);
        }
        if ($length < \strlen($spread)) {
            $length = strlen($spread);
        }
        return $length;
    }

    private static function getScale(string $ask, string $bid, string $spread): int
    {
        $scale1 = Add::getScale($ask, $bid);
        $scale2 = Add::getScale($ask, $spread);
        return $scale1 > $scale2 ? $scale1 : $scale2;
    }
}
