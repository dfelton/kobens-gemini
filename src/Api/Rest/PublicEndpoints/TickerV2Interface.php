<?php

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

interface TickerV2Interface
{
    public function getData(string $symbol): \stdClass;
}
