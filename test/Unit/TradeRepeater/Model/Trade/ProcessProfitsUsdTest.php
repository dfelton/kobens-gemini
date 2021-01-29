<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater\Model\Trade;

use PHPUnit\Framework\TestCase;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Trade\CalculateCompletedProfits;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;

class ProcessProfitsUsdTest extends TestCase
{
    public function testProcessProfits()
    {
        $trade = new Trade(
            null,
            0,
            0,
            'SELL_FILLED',
            'aaveusd',
            '0.002',
            '243',
            '0.00195',
            '255.15',
            null,
            null,
            null,
            null,
            null,
            '{"buy_price":"243","sell_price":"255.15"}'
        );
        $this->processProfits($trade);
    }

    private function processProfits(Trade $trade): void
    {
        $pair = Pair::getInstance($trade->getSymbol());
        if ($pair->getQuote()->getSymbol() !== 'usd') {
            return;
        }

        $usd = CalculateCompletedProfits::get($trade)['usd'];

        $backToTrade = Multiply::getResult($usd, '0.5');
        $amountToAdd = bcdiv(
            $backToTrade,
            $trade->getBuyPrice(),
            Add::getScale($pair->getMinOrderIncrement(), $pair->getMinOrderIncrement())
        );

        if (Compare::getResult($amountToAdd, $pair->getMinOrderIncrement()) !== Compare::RIGHT_GREATER_THAN) {
            $buyAmount = Add::getResult($trade->getBuyAmount(), $amountToAdd);
            $sellAmount = Add::getResult($trade->getSellAmount(), $amountToAdd);
        }
        return;
    }
}