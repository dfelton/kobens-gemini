<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Watcher;

use Kobens\Gemini\TradeRepeater\Watcher\Helper\DataInterface;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\OutputInterface;

final class MarketSpread implements TableReportInterface
{
    public static function getTable(OutputInterface $output, DataInterface $data, string $symbol): Table
    {
        $result = $data->getPriceResult($symbol);
        $spread = Subtract::getResult($result->getAsk(), $result->getBid());
        $table = new Table($output);
        $table
            ->setHeaders(
                [
                    new TableCell('<options=underscore>Market Data</>', ['colspan' => 2])
                ]
            )
            ->setRows(
                [
                    ['Current Ask', "<fg=red>{$result->getAsk()}</>"],
                    ['Current Bid', "<fg=green>{$result->getBid()}</>"],
                    ['Spread', $spread],
                ]
            );
        return $table;
    }
}
