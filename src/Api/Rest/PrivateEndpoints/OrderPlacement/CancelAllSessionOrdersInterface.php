<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

interface CancelAllSessionOrdersInterface
{
    public function cancelSessionOrders(): \stdClass;
}
