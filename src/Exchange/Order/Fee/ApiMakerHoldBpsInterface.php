<?php

declare(strict_types=1);

namespace Kobens\Gemini\Exchange\Order\Fee;

interface ApiMakerHoldBpsInterface
{
    public function get(): string;
}
