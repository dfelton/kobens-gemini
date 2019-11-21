<?php

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;;

interface BuyPlacedInterface extends ActionInterface
{
    public function setNextState(int $id): bool;
}
