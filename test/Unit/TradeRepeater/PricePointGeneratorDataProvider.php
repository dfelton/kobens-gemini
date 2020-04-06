<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater;

use Kobens\Gemini\Exchange\Currency\Pair;

class PricePointGeneratorDataProvider
{
    public function priceEndsCorrectly(): array
    {
        return [
            [
                '32.5',
                '33.32',
                Pair::getInstance('btcusd'),
                '0.00014000',
                '10',
                '33',
                '2.5',
                '0.025',
            ]
        ];
    }
}
