<?php

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

interface ArchiveInterface
{
    public function addArchive(string $symbol, string $buyClientOrderId, string $buyOrderId, string $buyAmount, string $buyPrice, string $sellClientOrderId, string $sellOrderId, string $sellAmount, string $sellPrice): void;
}
