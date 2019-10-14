<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

interface BuyReadyInterface extends DataResourceInterface
{
    public function setNextState(int $id, string $buyClientOrderId): void;

    public function setErrorState(int $id, string $message): void;

    public function resetState(int $id): void;
}
