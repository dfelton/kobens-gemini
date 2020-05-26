<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

interface GetPastTradesInterface
{
    public const LIMIT_MAX = 500;
    public const LIMIT_MIN = 1;

    public function getTrades(string $symbol, int $timestampms = null, int $limitTrades = null): array;
}
