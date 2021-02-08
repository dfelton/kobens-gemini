<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade\Profits;

use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Processor\ReInvest;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Kobens\Gemini\TradeRepeater\Model\Trade\CalculateCompletedProfits;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Processor\TopWeekly;

/**
 * When a repeater position has completed a sale, we have
 * realized profits. This processor is responsible for
 * determining what to do with those profits.
 *
 * An example may be to re-invest a portion back into
 * purchasing more base currency next time. Or, allocating
 * quote currency into other buckets used for other purposes.
 */
final class Processor implements ProcessorInterface
{
    private TopWeekly $topWeekly;

    private ReInvest $reinvest;

    public function __construct(
        ReInvest $reinvest,
        TradeResource $tradeResource,
        TopWeekly $topWeekly
    ) {
        $this->reinvest = $reinvest;
        $this->topWeekly = $topWeekly;
    }

    public function execute(Trade $trade): void
    {
        $pair = Pair::getInstance($trade->getSymbol());
        if ($pair->getQuote()->getSymbol() !== 'usd') {
            return;
        }

        $profits = CalculateCompletedProfits::get($trade);
        $quoteAmount = $profits[$pair->getQuote()->getSymbol()];
        $quoteAmount = $this->reinvest->execute($quoteAmount, $trade);
        $quoteAmount = $this->topWeekly->execute($quoteAmount);

        // TODO: put the remaining $quoteAmount and $profits[$pair->getBase()->getSymbol()] into a db table "bucket" for use by a future cron
    }
}
