<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

interface SellSentInterface extends DataResourceInterface
{
    public function setNextState(int $id, string $orderId, string $price): void;
}
