<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Processor;

use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Update;
use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount\Calculator;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;

final class ReInvest
{
    private Update $update;

    public function __construct(
        Update $update
    ) {
        $this->update = $update;
    }

    public function execute(string $quoteAmount, Trade $trade, string $use = '0.10'): string
    {
        $pair = Pair::getInstance($trade->getSymbol());
        $reinvestInSamePosition = Multiply::getResult($quoteAmount, $use);
        $calculation = new Calculator($pair, $reinvestInSamePosition, $trade->getBuyPrice());
        if (Compare::getResult($calculation->getBaseAmount(), $pair->getMinOrderIncrement()) !== Compare::RIGHT_GREATER_THAN) {
            $buyAmount = Add::getResult($trade->getBuyAmount(), $calculation->getBaseAmount());
            $sellAmount = Add::getResult($trade->getSellAmount(), $calculation->getBaseAmount());
            $this->update->updateAmounts($trade->getId(), $buyAmount, $sellAmount);
        }
        return Subtract::getResult(
            $quoteAmount,
            Subtract::getResult($reinvestInSamePosition, $calculation->getQuoteRemaining())
        );
    }
}
