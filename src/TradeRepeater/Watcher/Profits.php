<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Watcher;

use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Symfony\Component\Console\Helper\TableCell;

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

    public function get(OutputInterface $output): Table
    {
        $profits = $this->getData();
        $table = new Table($output);
        $table->setColumnMaxWidth(0, 10);
        $table->setHeaderTitle(sprintf('Profits Since %s', $profits['date']));
        $table->setHeaders(['Asset', 'Amount', 'Amount Notional']);

        $totalNotional = '0';
        foreach ($profits['profits'] as $symbol => $amount) {
            $notional = $this->getNotional($symbol, $amount);
            $table->addRow([strtoupper($symbol), $amount, $notional]);
            $totalNotional = Add::getResult($totalNotional, $notional);
        }
        $table->addRow([
            new TableCell('<fg=green>Total Notional</>', ['colspan' => 2]),
            '$' . number_format((float)$totalNotional, 2),
        ]);
        return $table;
    }

    /**
     * TODO: Filter by past month
     */
    private function getData()
    {
        $results = $this->table->select(function (Select $sql) {
            $sql->order('created_at ASC');
        });
        $profits = [];
        $date = null;
        foreach ($results as $result) {
            if ($date === null) {
                $date = $result->created_at;
            }
            foreach ($this->getProfits($result) as $symbol => $amount) {
                if (($profits[$symbol] ?? null) === null) {
                    $profits[$symbol] = '0';
                }
                $profits[$symbol] = Add::getResult($profits[$symbol], $amount);
            }
        }
        ksort($profits);
        return [
            'date' => $date,
            'profits' => $profits,
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
