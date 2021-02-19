<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\CliMonitor;

use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Divide;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

final class Profits
{
    private TableGateway $table;

    private Adapter $adapter;

    private GetPriceInterface $getPrice;

    public function __construct(
        Adapter $adapter,
        GetPriceInterface $getPrice
    ) {
        $this->adapter = $adapter;
        $this->table = new TableGateway('trade_repeater_archive', $adapter);
        $this->getPrice = $getPrice;
    }

    /**
     * @param OutputInterface $output
     * @return Table
     */
    public function get(OutputInterface $output): Table
    {
        $data = $this->getData();
        $table = new Table($output);
        $table->setHeaders([
            [
                '',
                new TableCell(sprintf('All Time Since %s', $data['all_time']['date']), ['colspan' => 2]),
                new TableCell('Past 30 Days', ['colspan' => 2]),
                new TableCell('Past 7 Days', ['colspan' => 2]),
                new TableCell('Past 24 Hours', ['colspan' => 2]),
            ],
            ['Asset', 'Amount', 'Amount Notional', 'Amount', 'Amount Notional', 'Amount', 'Amount Notional', 'Amount', 'Amount Notional'],
        ]);
        $cellsAllTime = $this->getCells($data['all_time']);
        $cellsPast30d = $this->getCells($data['30d']);
        $cellsPast7d = $this->getCells($data['7d']);
        $cellsPast1d = $this->getCells($data['1d']);
        foreach ($cellsAllTime['cells'] as $symbol => $cellData) {
            $row = array_merge(
                [strtoupper($symbol)],
                $cellData,
                $cellsPast30d['cells'][$symbol] ?? [],
                $cellsPast7d['cells'][$symbol] ?? [],
                $cellsPast1d['cells'][$symbol] ?? []
            );
            $table->addRow($row);
        }
        $table->addRow(['', '', '', '', '', '', '', '', '']);
        $table->addRow([
            new TableCell('Total Notional', ['colspan' => 2]),
            $cellsAllTime['total_notional'],
            '',
            $cellsPast30d['total_notional'],
            '',
            $cellsPast7d['total_notional'],
            '',
            $cellsPast1d['total_notional'],
        ]);
        $table->addRow([
            new TableCell('Daily Average', ['colspan' => 2]),
            $cellsAllTime['daily_average'],
            '',
            $cellsPast30d['daily_average'],
            '',
            $cellsPast7d['daily_average'],
            '',
            $cellsPast1d['daily_average'],
        ]);
        $table->addRow([
            new TableCell('Projected Yearly', ['colspan' => 2]),
            $cellsAllTime['projected_yearly'],
            '',
            $cellsPast30d['projected_yearly'],
            '',
            $cellsPast7d['projected_yearly'],
            '',
            $cellsPast1d['projected_yearly'],
        ]);
        return $table;
    }

    private function getCells(array $profits): array
    {
        $cells = [];
        $totalNotional = '0';
        $notionals = [];
        $longestNotional = 0;
        $longestAmount = 0;
        foreach ($profits['profits'] as $symbol => $amount) {
            $notional = $this->getNotional($symbol, $amount);

            $notionals[$symbol] = bcadd($notional, '0', 8);
            $profits['profits'][$symbol] = bcadd($amount, '0', 8);

            $lenNotional = strlen($notionals[$symbol]);
            $lenAmount = strlen($profits['profits'][$symbol]);
            $longestAmount = $lenAmount > $longestAmount ? $lenAmount : $longestAmount;
            $longestNotional = $lenNotional > $longestNotional ? $lenNotional : $longestNotional;

            $totalNotional = Add::getResult($totalNotional, $notional);
        }

        $longestAmount = $longestAmount < 18 ? 18 : $longestAmount;
        $longestNotional = $longestNotional < 18 ? 18 : $longestNotional;

        foreach (array_keys($notionals) as $symbol) {
            $row = [
                str_pad($profits['profits'][$symbol], $longestAmount, ' ', STR_PAD_LEFT),
                str_pad($notionals[$symbol], $longestNotional, ' ', STR_PAD_LEFT)
            ];
            if (strtoupper($symbol) === 'USD') {
                $row[0] = '<fg=green>' . $row[0] . '</>';
                $row[1] = '<fg=green>' . $row[1] . '</>';
            }
            $cells[$symbol] = $row;
        }
        $dailyAverage = Divide::getResult($totalNotional, $profits['days'], 2);
        return [
            'cells' => $cells,
            'total_notional' => str_pad('$' . number_format((float) $totalNotional, 2), $longestNotional, ' ', STR_PAD_LEFT),
            'daily_average' => str_pad('$' . $dailyAverage, $longestNotional, ' ', STR_PAD_LEFT),
            'projected_yearly' => str_pad('$' . number_format((float) Multiply::getResult($dailyAverage, '365'), 2), $longestNotional, ' ', STR_PAD_LEFT),
        ];
    }

    /**
     * TODO: Add Filter options
     */
    private function getData()
    {
        // TODO: isolate into its own method and implement pagination for fetching
        $results = $this->table->select(function (Select $sql) {
            $sql->columns(['buy_amount', 'buy_price', 'sell_amount', 'sell_price', 'sell_fill_timestamp', 'symbol']);
            $sql->order('sell_fill_timestamp ASC');

            // It is only null for brand new records which the repeater:archiver:fill-time command has not yet processed
            $sql->where('sell_fill_timestamp IS NOT NULL');
        });

        $profits = [];
        $profits1d = [];
        $profits7d = [];
        $profits30d = [];
        $date = null;
        $timestamp1d = time() - 86400;
        $timestamp7d = time() - 604800;
        $timestamp30d = time() - 2592000;
        foreach ($results as $result) {
            if ($date === null) {
                $date = $result->sell_fill_timestamp;
            }

            foreach ($this->getProfits($result) as $symbol => $amount) {
                if (($profits[$symbol] ?? null) === null) {
                    $profits[$symbol] = '0';
                }
                $profits[$symbol] = Add::getResult($profits[$symbol], $amount);

                $sellFillTimestamp = strtotime($result->sell_fill_timestamp);
                if ($sellFillTimestamp > $timestamp1d) {
                    if (($profits1d[$symbol] ?? null) === null) {
                        $profits1d[$symbol] = '0';
                    }
                    $profits1d[$symbol] = Add::getResult($profits1d[$symbol], $amount);
                }
                if ($sellFillTimestamp > $timestamp7d) {
                    if (($profits7d[$symbol] ?? null) === null) {
                        $profits7d[$symbol] = '0';
                    }
                    $profits7d[$symbol] = Add::getResult($profits7d[$symbol], $amount);
                }
                if ($sellFillTimestamp > $timestamp30d) {
                    if (($profits30d[$symbol] ?? null) === null) {
                        $profits30d[$symbol] = '0';
                    }
                    $profits30d[$symbol] = Add::getResult($profits30d[$symbol], $amount);
                }
            }
        }

        ksort($profits);
        ksort($profits1d);
        ksort($profits7d);
        ksort($profits30d);

        return [
            'all_time' => [
                'date' => $date,
                'profits' => $profits,
                'days' => (string) round((time() - strtotime($date)) / (60 * 60 * 24), 2),
            ],
            '1d' => [
                'date' => date('Y-m-d H:s:i', $timestamp1d),
                'profits' => $profits1d,
                'days' => '1'
            ],
            '7d' => [
                'date' => date('Y-m-d H:s:i', $timestamp7d),
                'profits' => $profits7d,
                'days' => '7'
            ],
            '30d' => [
                'date' => date('Y-m-d H:s:i', $timestamp7d),
                'profits' => $profits30d,
                'days' => '30'
            ],
        ];
    }

    /**
     * FIXME: Need to lookup actual transaction data from transaction table to ensure confidence in fee amount for order (right now we're assuming always a 10BPS)
     *
     * @param \stdClass $data
     * @return array
     */
    private function getProfits($data): array
    {
        $buyAmount = Multiply::getResult($data->buy_amount, $data->buy_price);
        $buyFee = Multiply::getResult($buyAmount, '0.0010');
        $sellProceeds = Multiply::getResult($data->sell_amount, $data->sell_price);
        $sellFee = Multiply::getResult($sellProceeds, '0.0010');

        $baseProfits = Subtract::getResult($data->buy_amount, $data->sell_amount);
        $quoteProfits = Subtract::getResult(
            Subtract::getResult(
                Subtract::getResult(
                    $sellProceeds,
                    $sellFee
                ),
                $buyAmount
            ),
            $buyFee
        );

        $pair = Pair::getInstance($data->symbol);
        $result = [];
        if (Compare::getResult('0', $baseProfits) === Compare::RIGHT_GREATER_THAN) {
            $result[$pair->getBase()->getSymbol()] = $baseProfits;
        }
        if (Compare::getResult('0', $quoteProfits) === Compare::RIGHT_GREATER_THAN) {
            $result[$pair->getQuote()->getSymbol()] = $quoteProfits;
        }

        return $result;
    }

    private function getNotional(string $symbol, string $amount): string
    {
        return $symbol === 'usd'
            ? $amount
            : Multiply::getResult($amount, $this->getPrice->getBid($symbol . 'usd'));
    }
}
