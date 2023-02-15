<?php

declare(strict_types=1);

namespace Kobens\Gemini\Report;

use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Gemini\Report\YearToDateVolume\DataProviderInterface;

final class YearToDateVolume
{
    private DataProviderInterface $dataProvider;

    public function __construct(
        DataProviderInterface $dataProvider
    ) {
        $this->dataProvider = $dataProvider;
    }

    public function execute(int $year, string $quoteSymbol = 'usd'): array
    {
        $totals = [];
        foreach (Pair::getAllInstances() as $pair) {
            if ($pair->getQuote()->getSymbol() === $quoteSymbol) {
                $totals[$pair->getBase()->getSymbol()] = $this->getTotal(
                    $pair->getSymbol(),
                    $year
                );
            }
        }
        return $totals;
    }

    private function getTotal(string $symbol, int $year): string
    {
        $total = '0';
        foreach ($this->getTrades($symbol, $year) as $trade) {
            $total = Add::getResult(
                $total,
                Multiply::getResult(
                    $trade->price,
                    $trade->amount
                )
            );
        }
        return $total;
    }
}
