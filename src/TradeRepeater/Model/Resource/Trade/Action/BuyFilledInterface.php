<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

interface BuyFilledInterface extends ActionInterface
{
    public function setNextState(int $id, string $sellClientOrderId): void;

    public function resetState(int $id): void;
}
