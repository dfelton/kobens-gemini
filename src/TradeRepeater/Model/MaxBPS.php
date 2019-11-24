<?php

namespace Kobens\Gemini\TradeRepeater\Model;

final class MaxBPS implements MaxBPSInterface
{
    private const MAX_BPS = 10;

    public function getMaxBPS(): int
    {
        return self::MAX_BPS;
    }
}
