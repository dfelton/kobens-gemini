<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

interface CancelAllActiveOrdersInterface
{
    public function cancelAll(): \stdClass;
}
