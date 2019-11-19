<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

interface CancelAllActiveOrdersInterface
{
    public function cancelAll(): \stdClass;
}
