<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

interface TickerInterface
{
    public function getData(string $symbol): \stdClass;
}
