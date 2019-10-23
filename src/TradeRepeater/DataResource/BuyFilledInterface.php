<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

interface BuyFilledInterface extends DataResourceInterface
{
    public function setNextState(int $id, string $sellClientOrderId): void;

    public function resetState(int $id): void;
}
