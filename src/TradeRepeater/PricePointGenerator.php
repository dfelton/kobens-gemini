<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Exception;
use Kobens\Gemini\Exchange\Order\Fee\ApiMakerHoldBps;
use Kobens\Gemini\Exchange\Order\Fee\MaxApiMakerBps;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\Result;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;

final class PricePointGenerator
{
    /**
     * FIXME: Implement logic with \Kobens\Exchange\PairInterface::getMinPriceIncrement to verify the increment is valid
     * FIXME: Implement logic with \Kobens\Exchange\PairInterface::getMinPriceIncrement to verify the buy start price is valid
     *
     * @param PairInterface $pair Currency Pair which to generate PricePoint models for
     * @param string $buyAmount Amount of base currency to purchase with each PricePoint
     * @param string $priceStart Quote currency buy price which to first generate a PricePoint for
     * @param string $priceEnd Quote currency buy price which to not generate PricePoint models beyond
     * @param string $increment Amount Quote currency buy price should be incremented between each PricePoint
     * @param string $priceChange Multiplier to use to determine Quote currency sell price in relation to a PricePoint's buy price ("1" would result in no price change, "2" result in a 100% gain in price)
     * @param string $saveAmount Amount of base currency to HODL from the buy order (determines amount to sell in a PricePoint)
     * @param bool $byPassMinOrderSize Do not throw an exception for min order size violations.
     * @return Result
     * @throws \Exception
     */
    public static function get(PairInterface $pair, string $buyAmount, string $priceStart, string $priceEnd, string $increment, string $priceChange, string $saveAmount = '0', $byPassMinOrderSize = false): Result
    {
        $sellAmount = Subtract::getResult($buyAmount, $saveAmount);
        if (!$byPassMinOrderSize) {
            self::validateOrderSize($pair, $buyAmount, $sellAmount);
        }
        $orders = [];
        $variableIncrement = false;
        $price = $priceStart;
        while (Compare::getResult($price, $priceEnd) !== Compare::RIGHT_LESS_THAN) {
            $sellData = self::getSellPrice($price, $priceChange, $pair);
            $variableIncrement = $variableIncrement || $sellData['isExact'];
            $pricePoint = new PricePoint(
                $buyAmount,
                $price,
                $sellAmount,
                $sellData['sell_price'],
                MaxApiMakerBps::get(),
                ApiMakerHoldBps::get()
            );
            self::validateHasGains($pricePoint);
            $orders[] = $pricePoint;
            $price = Add::getResult($price, $increment);
        }
        return new Result($orders, $variableIncrement);
    }

    private static function validateOrderSize(PairInterface $pair, string $buyAmount, string $sellAmount): void
    {
        if (Compare::getResult($pair->getMinOrderSize(), $buyAmount) === Compare::LEFT_GREATER_THAN) {
            throw new Exception(sprintf(
                'Buy amount "%s" is invalid. Minimum order size is "%s".',
                $buyAmount,
                $pair->getMinOrderSize()
            ));
        } elseif (Compare::getResult($pair->getMinOrderSize(), $sellAmount) === Compare::LEFT_GREATER_THAN) {
            throw new Exception(sprintf(
                'Sell amount "%s" is invalid. Minimum order size is "%s".',
                $sellAmount,
                $pair->getMinOrderSize()
            ));
        }
    }

    /**
     * @param PricePoint $pricePoint
     * @throws Exception
     */
    private static function validateHasGains(PricePoint $pricePoint): void
    {
        // "Potentially" in the wording here because fees are variable, and we assume the worst
        if (Compare::getResult($pricePoint->getProfitQuote(), '0') === Compare::RIGHT_GREATER_THAN) {
            throw new Exception('Params yield potential quote currency losses');
        } elseif (Compare::getResult($pricePoint->getProfitBase(), '0') === Compare::RIGHT_GREATER_THAN) {
            throw new Exception('Params yield potential base currency losses.');
        } elseif (
            Compare::getResult($pricePoint->getProfitQuote(), '0') === Compare::EQUAL &&
            Compare::getResult($pricePoint->getProfitBase(), '0') === Compare::EQUAL
        ) {
            throw new Exception('Params yield potentially no gains.');
        }
    }

    /**
     * Determine the sell price based off
     *      - Gain goal
     *      - Quote minimum price increment
     *
     * If the exact sell price for the desired "sell after gain" were to result in a value that does not
     * comply with the minimum price increment of the quote asset, then round down the exact sell price for the quote
     * asset to the nearest minimum increment amount, and then increment the quote price increment by the minimum
     * allowed amount.
     *
     * @param string $priceStart
     * @param string $priceChange
     * @param PairInterface $pair
     * @return array
     */
    private static function getSellPrice(string $priceStart, string $priceChange, PairInterface $pair): array
    {
        $exact = Multiply::getResult($priceStart, $priceChange);
        $roundedDown = \bcadd($exact, '0', $pair->getQuote()->getScale());
        if (Compare::getResult($exact, $roundedDown) === Compare::EQUAL) {
            $sellPrice = $exact;
            $isExact = true;
        } else {
            $sellPrice = Add::getResult($roundedDown, $pair->getMinPriceIncrement());
            $isExact = false;
        }
        return [
            'sell_price' => $sellPrice,
            'isExact' => $isExact,
        ];
    }
}
