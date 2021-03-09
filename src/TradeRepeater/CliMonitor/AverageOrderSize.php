<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\CliMonitor;

use Kobens\Currency\Currency;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Divide;
use Zend\Db\Adapter\Adapter;

final class AverageOrderSize
{
    private Adapter $adapter;

    public function __construct(
        Adapter $adapter
    ) {
        $this->adapter = $adapter;
    }

    public function getAverageQuoteAmount(string $quoteCurrency, string $baseCurrency = null): array
    {
        $total = '0';
        $count = 0;
        $min = '0';
        foreach ($this->getNext(null, $baseCurrency) as $row) {
            ++$count;
            $costBasis = Multiply::getResult($row['buy_amount'], $row['buy_price']);
            $depositAmount = Multiply::getResult($costBasis, '0.0035'); // TODO: reference constant or something... fuck I'm lazy at this
            $total = Add::getResult(
                $total,
                Add::getResult($costBasis, $depositAmount)
            );
            $min = Add::getResult($min, Multiply::getResult(Pair::getInstance($row['symbol'])->getMinOrderIncrement(), $row['buy_price']));
        }
        return [
            'count' => $count,
            'avg' => $count === 0
                ? '0'
                : Divide::getResult(
                    $total,
                    (string) $count,
                    $quoteCurrency === 'usd' ? 6 : Currency::getInstance($quoteCurrency)->getScale()
                ),
            'min' => $count === 0
                ? '0'
                : Add::getResult(Divide::getResult($min, (string) $count, 20), '0'),
        ];
    }

    public function getAverageBaseAmount(string $baseCurrency): array
    {
        $total = '0';
        $count = 0;
        foreach ($this->getNext(null, $baseCurrency) as $row) {
            ++$count;
            $total = Add::getResult($total, $row['buy_amount']);
        }
        return $count === 0
            ? '0'
            : Divide::getResult(
                $total,
                (string) $count,
                Currency::getInstance($baseCurrency)->getScale()
            );
    }

    private function getNext(string $quoteCurrency = null, string $baseCurrency = null, bool $countBase = false): \Generator
    {
        $lastId = 0;
        do {
            $results = $this->adapter->query(
                'SELECT id, symbol, buy_amount, buy_price FROM trade_repeater WHERE id > :id LIMIT 1000'
            )->execute(['id' => $lastId]);
            foreach ($results as $result) {
                $lastId = $result['id'];
                $pair = Pair::getInstance($result['symbol']);
                if (
                    ($quoteCurrency === null || $pair->getQuote()->getSymbol() === $quoteCurrency) &&
                    ($baseCurrency === null || $pair->getBase()->getSymbol() === $baseCurrency)
                ) {
                    yield $result;
                }
            }
        } while (count($results));
    }
}
