<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Exception;
use Kobens\Gemini\Exception\Order\MinimumAmountException;
use Kobens\Gemini\Exchange\Order\Fee\ApiMakerHoldBpsInterface;
use Kobens\Gemini\Exchange\Order\Fee\MaxApiMakerBps;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\Result;
use Kobens\Math\IsEvenlyDivisible;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;

final class PricePointGenerator implements PricePointGeneratorInterface
{
    private ApiMakerHoldBpsInterface $apiMakerHoldBps;

    public function __construct(
        ApiMakerHoldBpsInterface $apiMakerHoldBps
    ) {
        $this->apiMakerHoldBps = $apiMakerHoldBps;
    }

    /**
     * {@inheritDoc}
     * @see \Kobens\Gemini\TradeRepeater\PricePointGeneratorInterface::get()
     */
    public function get(
        PairInterface $pair,
        string $buyAmount,
        string $priceStart,
        string $priceEnd,
        string $increment,
        string $priceChange,
        string $saveAmount = '0',
        $byPassMinOrderSize = false,
        $incrementByPercent = false
    ): Result {
        $sellAmount = Subtract::getResult($buyAmount, $saveAmount);
        if (!$byPassMinOrderSize) {
            $this->validateOrderSize($pair, $buyAmount, $sellAmount);
        }

        $this->validatePriceStart($pair, $priceStart);
        if ($incrementByPercent === false) {
            $this->validatePriceIncrement($pair, $increment);
        }

        $orders = [];
        $variableIncrement = false;
        $price = $priceStart;
        while (Compare::getResult($price, $priceEnd) !== Compare::RIGHT_LESS_THAN) {
            $sellData = $this->getSellPrice($price, $priceChange, $pair);
            $variableIncrement = $variableIncrement || ($sellData['isExact'] === false);
            $pricePoint = new PricePoint(
                $buyAmount,
                $price,
                $sellAmount,
                $sellData['sell_price'],
                MaxApiMakerBps::get(),
                $this->apiMakerHoldBps->get()
            );
            $this->validateHasGains($pricePoint);
            $orders[] = $pricePoint;
            if (Compare::getResult($increment, '0') === Compare::EQUAL || Compare::getResult($priceStart, $priceEnd) === Compare::EQUAL) {
                break;
            }
            if ($incrementByPercent === false) {
                $price = Add::getResult($price, $increment);
            } else {
                $priceIncrease = bcmul(
                    $price,
                    $increment,
                    Add::getScale($pair->getMinPriceIncrement(), $pair->getMinPriceIncrement())
                );
                if (Compare::getResult($priceIncrease, $pair->getMinPriceIncrement()) === Compare::RIGHT_GREATER_THAN) {
                    $priceIncrease = $pair->getMinPriceIncrement();
                }
                $price = Add::getResult($price, $priceIncrease);
            }
        }
        return new Result($orders, $variableIncrement);
    }

    /**
     * @param PairInterface $pair
     * @param string $buyAmount
     * @param string $sellAmount
     * @throws Exception
     */
    private function validateOrderSize(PairInterface $pair, string $buyAmount, string $sellAmount): void
    {
        if (Compare::getResult($pair->getMinOrderSize(), $buyAmount) === Compare::LEFT_GREATER_THAN) {
            throw new MinimumAmountException(sprintf(
                'Buy amount "%s" is invalid. Minimum order size is "%s".',
                $buyAmount,
                $pair->getMinOrderSize()
            ));
        } elseif (Compare::getResult($pair->getMinOrderSize(), $sellAmount) === Compare::LEFT_GREATER_THAN) {
            throw new MinimumAmountException(sprintf(
                'Sell amount "%s" is invalid. Minimum order size is "%s".',
                $sellAmount,
                $pair->getMinOrderSize()
            ));
        }
    }

    private function validatePriceStart(PairInterface $pair, string $priceStart): void
    {
        $compare = Compare::getResult($priceStart, $pair->getMinPriceIncrement());
        if ($compare === Compare::EQUAL) {
            return;
        }
        if ($compare === Compare::LEFT_LESS_THAN) {
            throw new \InvalidArgumentException(sprintf(
                'Start price of "%s" is invalid. Minimum start price is "%s".',
                $priceStart,
                $pair->getMinOrderSize()
            ));
        }
        if (IsEvenlyDivisible::getResult($priceStart, $pair->getMinPriceIncrement()) === false) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid start price of "%s". Start price must be evenly divisible by "%s".',
                $priceStart,
                $pair->getMinPriceIncrement()
            ));
        }
    }

    private function validatePriceIncrement(PairInterface $pair, string $increment): void
    {
        if (IsEvenlyDivisible::getResult($increment, $pair->getMinPriceIncrement()) === false) {
            throw new \InvalidArgumentException(\sprintf(
                'Price increment of "%s" is invalid. Price increment must be evenly divisible by "%s".',
                $increment,
                $pair->getMinPriceIncrement()
            ));
        }
    }

    /**
     * @param PricePoint $pricePoint
     * @throws Exception
     */
    private function validateHasGains(PricePoint $pricePoint): void
    {
        // "Potentially" in the wording here because fees are variable, and we assume the worst
        if (Compare::getResult($pricePoint->getProfitQuote(), '0') === Compare::RIGHT_GREATER_THAN) {
            throw new Exception('Params yield potential quote currency losses.');
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
    private function getSellPrice(string $priceStart, string $priceChange, PairInterface $pair): array
    {
        $exact = Multiply::getResult($priceStart, $priceChange);
        $scale = strpos($pair->getMinPriceIncrement(), '.') === false
            ? 0
            : strlen(explode('.', $pair->getMinPriceIncrement())[1]);
        $roundedDown = \bcadd($exact, '0', $scale);

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
