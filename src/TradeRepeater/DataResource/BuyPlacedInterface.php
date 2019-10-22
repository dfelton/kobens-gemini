<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

interface BuyPlacedInterface extends DataResourceInterface
{
    public function setNextState(int $id): void;
}
