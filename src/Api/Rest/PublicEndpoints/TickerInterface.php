<?php

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

interface TickerInterface
{
    public function getData(string $symbol): \stdClass;
}
