<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\UsdProfitsDistributor;

interface RecordSelectorInterface
{
    /**
     * Yields \Kobens\Gemini\TradeRepeater\Model\Trade objects
     *
     * @return \Generator
     * @see \Kobens\Gemini\TradeRepeater\Model\Trade
     */
    public function get(): \Generator;
}
