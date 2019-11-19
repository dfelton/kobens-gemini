<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

interface CancelOrderInterface
{
    public function cancel(int $orderId): \stdClass;
}
