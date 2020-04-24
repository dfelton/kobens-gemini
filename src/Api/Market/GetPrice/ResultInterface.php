<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Market\GetPrice;

interface ResultInterface
{
    public function getAsk(): string;

    public function getBid(): string;
}
