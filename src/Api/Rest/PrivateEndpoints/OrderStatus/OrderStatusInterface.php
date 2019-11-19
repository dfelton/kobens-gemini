<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

interface OrderStatusInterface
{
    public function getStatus(int $orderId): \stdClass;
}
