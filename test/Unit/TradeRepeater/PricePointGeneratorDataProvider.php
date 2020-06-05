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
                '1.025',
            ]
        ];
    }

    public function setsHasVariablePriceIncrementCorrectly(): array
    {
        return [
            [Pair::getInstance('btcusd'), '1', '2.5', '10', '2.5', '1.025', true],
            [Pair::getInstance('btcusd'), '1', '10', '100', '10', '1.025', false],
        ];
    }
}
