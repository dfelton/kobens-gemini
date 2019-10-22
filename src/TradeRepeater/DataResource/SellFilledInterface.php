<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

interface SellFilledInterface extends DataResourceInterface
{
    public function setNextState(int $id): void;
}
