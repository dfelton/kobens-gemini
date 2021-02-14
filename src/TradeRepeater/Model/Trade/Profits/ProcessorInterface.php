<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade\Profits;

use Kobens\Gemini\TradeRepeater\Model\Trade;

/**
 * When a repeater position has completed a sale, we have
 * realized profits. This processor is responsible for
 * determining what to do with those profits.
 *
 * An example may be to re-invest a portion back into
 * purchasing more base currency next time. Or, allocating
 * quote currency into other buckets used for other purposes.
 */
interface ProcessorInterface
{
    public function execute(Trade $trade): void;
}
