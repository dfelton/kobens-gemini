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
     * @return Table[]
     */
    public function get(OutputInterface $output): array
    {
        $profits = $this->getData();
        return [
            $this->getTable($output, $profits['all_time'], sprintf('All Time (since %s)', $profits['all_time']['date'])),
            $this->getTable($output, $profits['past_week'], sprintf('Past 7 Days')),
        ];
    }

    private function getTable(OutputInterface $output, array $profits, string $tableTitle): Table
    {
        $table = new Table($output);
        $table->setColumnMaxWidth(0, 10);
        $table->setHeaderTitle($tableTitle);
        $table->setHeaders(['Asset', 'Amount', 'Amount Notional']);

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
                strtoupper($symbol),
                str_pad($profits['profits'][$symbol], $longestAmount, ' ', STR_PAD_LEFT),
                str_pad($notionals[$symbol], $longestNotional, ' ', STR_PAD_LEFT)
            ];
            if ($row[0] === 'USD') {
                $row[0] = '<fg=green>' . $row[0] . '</>';
                $row[1] = '<fg=green>' . $row[1] . '</>';
                $row[2] = '<fg=green>' . $row[2] . '</>';
            }
            $table->addRow($row);
        }

        $table->addRow([
            new TableCell('', ['colspan' => 3]),
        ]);
        $table->addRow([
            new TableCell('<fg=green>Total Notional</>', ['colspan' => 2]),
            str_pad('$' . number_format((float) $totalNotional, 2), $longestNotional, ' ', STR_PAD_LEFT),
        ]);
        $dailyAverage = Divide::getResult($totalNotional, $profits['days'], 2);
        $table->addRow([
            new TableCell('<fg=green>Daily Average</>', ['colspan' => 2]),
            str_pad('$' . $dailyAverage, $longestNotional, ' ', STR_PAD_LEFT),
        ]);
        $table->addRow([
            new TableCell('<fg=green>Projected Yearly</>', ['colspan' => 2]),
            str_pad('$' . number_format((float) Multiply::getResult($dailyAverage, '365'), 2), $longestNotional, ' ', STR_PAD_LEFT),
        ]);
        return $table;
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
        $profitsPastWeek = [];
        $date = null;
        $timestampOneWeekAgo = time() - 604800;
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
                if ($sellFillTimestamp > $timestampOneWeekAgo) {
                    if (($profitsPastWeek[$symbol] ?? null) === null) {
                        $profitsPastWeek[$symbol] = '0';
                    }
                    $profitsPastWeek[$symbol] = Add::getResult($profitsPastWeek[$symbol], $amount);
                }
            }
        }

        ksort($profits);
        ksort($profitsPastWeek);

        return [
            'all_time' => [
                'date' => $date,
                'profits' => $profits,
                'days' => (string) round((time() - strtotime($date)) / (60 * 60 * 24), 2),
            ],
            'past_week' => [
                'date' => date('Y-m-d H:s:i', $timestampOneWeekAgo),
                'profits' => $profitsPastWeek,
                'days' => '7'
            ]
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
