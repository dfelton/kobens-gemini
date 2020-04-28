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
use Kobens\Math\BasicCalculator\Subtract;

final class PricePointGenerator
{
    /**
     * FIXME: Implement logic with \Kobens\Exchange\PairInterface::getMinPriceIncrement to verify the increment is valid
     * FIXME: Implement logic with \Kobens\Exchange\PairInterface::getMinPriceIncrement to verify the buy start price is valid
     *
     * @param PairInterface $pair
     * @param string $buyAmount
     * @param string $priceStart
     * @param string $priceEnd
     * @param string $increment
     * @param string $sellAfterGain
     * @param string $saveAmount
     * @throws \Exception
     * @return Result
     */
    public static function get(PairInterface $pair, string $buyAmount, string $priceStart, string $priceEnd, string $increment, string $sellAfterGain, string $saveAmount = '0'): Result
    {
        $sellAmount = Subtract::getResult($buyAmount, $saveAmount);
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
        $orders = [];
        $quote = $pair->getQuote();

        // TODO: remove this once issues mentioned in constructor are addressed.
        if ($pair->getSymbol() !== 'btcusd') {
            throw new \Exception(\sprintf('"%s" is not yet compatible with any pairs other than "btcusd".', self::class));
        }
        $hasVariablePriceIncrement = false;

        while (Compare::getResult($priceStart, $priceEnd) !== Compare::RIGHT_LESS_THAN) {
            /**
             * Determine the sell price based off
             *      - Gain goal
             *      - Quote minimum price increment
             *
             * If the exact sell price for the desired "sell after gain" were to result in a value that does not
             * comply with the minimum price increment of the quote asset, then round down the exact sell price for the quote
             * asset to the nearest minimum increment amount, and then increment the quote price increment by the minimum
             * allowed amount.
             */
            $precision = \strlen(\substr($sellAfterGain, \strpos($sellAfterGain, '.') + 1)) * $quote->getScale();

            $sellPriceExact = \bcmul($priceStart, $sellAfterGain, $precision);
            $sellPriceExact .= \str_repeat('0',  $precision - \strlen(\substr($sellPriceExact, \strpos($sellPriceExact, '.') + 1)));

            $sellPriceRoundedDown = \bcadd($sellPriceExact, '0', 2) . \str_repeat('0', $precision - 2);

            if ($sellPriceExact === $sellPriceRoundedDown) {
                $sellPrice = \bcadd($sellPriceRoundedDown, '.00', 2);
            } else {
                $sellPrice = \bcadd($sellPriceRoundedDown, '.01', 2);
                $hasVariablePriceIncrement = true;
            }
            $sellPrice = \bcadd($priceStart, $sellPrice, $quote->getScale());

            $pricePoint = new PricePoint(
                $buyAmount,
                $priceStart,
                $sellAmount,
                $sellPrice,
                MaxApiMakerBps::get(),
                ApiMakerHoldBps::get(),
                $hasVariablePriceIncrement
            );
            // "Potentially" in the wording here because fees are variable, and we assume the worst
            if (Compare::getResult($pricePoint->getProfitQuote(), '0') === Compare::RIGHT_GREATER_THAN) {
                throw new Exception('Params yield potential quote currency losses');
            } elseif(Compare::getResult($pricePoint->getProfitBase(), '0') === Compare::RIGHT_GREATER_THAN) {
                throw new Exception('Params yield potential base currency losses.');
            } elseif (
                Compare::getResult($pricePoint->getProfitQuote(), '0') === Compare::EQUAL &&
                Compare::getResult($pricePoint->getProfitBase(), '0') === Compare::EQUAL
            ) {
                throw new Exception('Params yield potentially no gains.');
            }
            $orders[] = $pricePoint;
            $priceStart = Add::getResult($priceStart, $increment);
        }

        return new Result($orders, $hasVariablePriceIncrement);
    }

}
