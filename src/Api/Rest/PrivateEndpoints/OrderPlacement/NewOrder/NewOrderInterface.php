<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;

interface NewOrderInterface
{
    public function place(PairInterface $pair, string $side, string $amount, string $price, string $clientOrderId = null): \stdClass;
}
