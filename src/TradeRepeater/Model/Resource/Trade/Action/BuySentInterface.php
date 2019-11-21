<?php

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

interface BuySentInterface extends ActionInterface
{
    public function setNextState(int $id, string $buyOrderId, string $buyPrice): void;

    public function setErrorState(int $id, string $message): void;
}
