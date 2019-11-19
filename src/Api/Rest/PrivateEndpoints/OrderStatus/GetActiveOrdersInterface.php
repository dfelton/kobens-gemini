<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

interface GetActiveOrdersInterface
{
    public function getOrders(): array;
}
