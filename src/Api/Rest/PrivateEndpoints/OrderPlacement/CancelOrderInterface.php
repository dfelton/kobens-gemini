<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

interface CancelOrderInterface
{
    public function cancel(int $orderId): \stdClass;
}
