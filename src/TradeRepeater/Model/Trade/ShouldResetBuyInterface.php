<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade;

use Kobens\Gemini\TradeRepeater\Model\Trade;

interface ShouldResetBuyInterface
{

    /**
     * @param Trade $trade
     * @return bool
     */
    public function get(Trade $trade): bool;
}
