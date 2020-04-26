<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

interface GetActiveOrdersInterface
{
    public function getOrders(): array;
}
