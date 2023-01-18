<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Market\GetPrice;

use Kobens\Gemini\Exception\Api\Market\GetPrice\NullPriceException;

class Result implements ResultInterface
{
    private string $bid;

    private string $ask;

    public function __construct(string $bid = null, string $ask = null)
    {
        if ($bid === null || $ask === null) {
            throw new NullPriceException();
        }
        $this->bid = $bid;
        $this->ask = $ask;
    }

    public function getAsk(): string
    {
        return $this->ask;
    }

    public function getBid(): string
    {
        return $this->bid;
    }
}
