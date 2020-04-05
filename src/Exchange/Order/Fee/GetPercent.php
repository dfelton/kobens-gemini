<?php

declare(strict_types=1);

namespace Kobens\Gemini\Exchange\Order\Fee;

use Kobens\Math\BasicCalculator\Divide;

final class GetPercent
{
    private function __construct()
    {
    }

    public static function get(string $bps): string
    {
        return Divide::getResult($bps, '10000', 5);
    }
}
