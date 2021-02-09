<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Exception;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\Result;

interface PricePointGeneratorInterface
{
    /**
     * @param PairInterface $pair       Currency Pair which to generate PricePoint models for
     * @param string $buyAmount         Amount of base currency to purchase with each PricePoint
     * @param string $priceStart        Quote currency buy price which to first generate a PricePoint for
     * @param string $priceEnd          Quote currency buy price which to not generate PricePoint models beyond
     * @param string $increment         Amount Quote currency buy price should be incremented between each PricePoint
     * @param string $priceChange       Multiplier to use to determine Quote currency sell price in relation to a PricePoint's buy price ("1" would result in no price change, "2" result in a 100% gain in price)
     * @param string $saveAmount        Amount of base currency to HODL from the buy order (determines amount to sell in a PricePoint)
     * @param bool $byPassMinOrderSize  Do not throw an exception for min order size violations.
     * @param bool $incrementByPercent
     * @return Result
     * @throws \Exception
     */
    public function get(PairInterface $pair, string $buyAmount, string $priceStart, string $priceEnd, string $increment, string $priceChange, string $saveAmount = '0', $byPassMinOrderSize = false, bool $incrementByPercent = false): Result;
}
