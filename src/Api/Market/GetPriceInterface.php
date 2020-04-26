<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Market;

use Kobens\Gemini\Api\Market\GetPrice\ResultInterface;

interface GetPriceInterface
{
    public function getAsk(string $symbol): string;

    public function getBid(string $symbol): string;

    public function getResult(string $symbol): ResultInterface;
}
