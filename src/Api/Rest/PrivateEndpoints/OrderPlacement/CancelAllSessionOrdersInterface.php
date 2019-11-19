<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;


interface CancelAllSessionOrdersInterface
{
    public function cancelSessionOrders(): \stdClass;
}
