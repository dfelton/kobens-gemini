<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

interface BuyPlacedInterface extends ActionInterface
{
    public function setNextState(int $id): bool;
}
