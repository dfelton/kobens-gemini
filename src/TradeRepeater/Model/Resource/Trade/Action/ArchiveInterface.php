<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

interface ArchiveInterface
{
    public function addArchive(string $symbol, string $buyClientOrderId, int $buyOrderId, string $buyAmount, string $buyPrice, string $sellClientOrderId, int $sellOrderId, string $sellAmount, string $sellPrice): void;
}
