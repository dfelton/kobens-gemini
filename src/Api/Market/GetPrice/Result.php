<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Market\GetPrice;

class Result implements ResultInterface
{
    private $bid;

    private $ask;

    public function __construct(string $bid, string $ask)
    {
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
