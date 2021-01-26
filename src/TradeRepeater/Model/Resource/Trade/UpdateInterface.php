<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade;

use Kobens\Gemini\TradeRepeater\Model\Trade;

interface UpdateInterface
{
    public function execute(Trade $trade): void;
}
