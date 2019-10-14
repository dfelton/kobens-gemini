<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

interface BuySentInterface extends DataResourceInterface
{
    public function setNextState(int $id, string $buyOrderId, string $buyPrice): void;
}
