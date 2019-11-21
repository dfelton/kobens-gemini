<?php

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

interface SellPlacedInterface extends ActionInterface
{
    public function setNextState(int $id): bool;
}
