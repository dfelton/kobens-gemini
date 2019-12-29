<?php

namespace Kobens\Gemini\Api\Market;

interface GetPriceInterface
{
    public function getAsk(string $symbol): string;

    public function getBid(string $symbol): string;
}
