<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

interface BuyReadyInterface extends ActionInterface
{
    public function setNextState(int $id, string $buyClientOrderId): void;

    public function resetState(int $id): void;
}
