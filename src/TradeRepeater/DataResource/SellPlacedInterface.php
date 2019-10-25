<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

interface SellPlacedInterface extends DataResourceInterface
{
    public function setNextState(int $id): bool;
}
