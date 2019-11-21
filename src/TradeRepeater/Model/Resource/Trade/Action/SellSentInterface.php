<?php

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

interface SellSentInterface extends ActionInterface
{
    public function setNextState(int $id, string $orderId, string $price): void;

    public function setErrorState(int $id, string $message): void;
}
