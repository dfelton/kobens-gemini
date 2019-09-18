<?php

namespace Kobens\Gemini\TradeRepeater;

interface StateStepperInterface
{
    public function getHealthyRecords(): \Generator;

    public function setNextState(int $id, array $args = []): bool;
}
