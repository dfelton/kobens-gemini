<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade\Profits;

use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Processor\ReInvest;
use Kobens\Gemini\TradeRepeater\Model\Trade\CalculateCompletedProfits;
use Kobens\Gemini\Exchange\Currency\Pair;

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
    private Bucket $bucket;

    private ReInvest $reinvest;

    public function __construct(
        ReInvest $reinvest,
        Bucket $bucket
    ) {
        $this->bucket = $bucket;
        $this->reinvest = $reinvest;
    }

    public function execute(Trade $trade): void
    {
        $quote = Pair::getInstance($trade->getSymbol())->getQuote()->getSymbol();
        $base = Pair::getInstance($trade->getSymbol())->getBase()->getSymbol();
        $profits = CalculateCompletedProfits::get($trade);
        $this->bucket->addToBucket($quote, $this->reinvest->execute($profits[$quote], $trade));
        $this->bucket->addToBucket($base, $profits[$base]);
    }
}
