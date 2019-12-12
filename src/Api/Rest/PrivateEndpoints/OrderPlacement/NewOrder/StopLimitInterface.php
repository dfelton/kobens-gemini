<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;

interface StopLimitInterface
{
    public function place(PairInterface $pair, string $side, string $amount, string $price, string $stopPrice, string $clientOrderId = null): \stdClass;
}
