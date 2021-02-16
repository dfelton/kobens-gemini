<?php

declare(strict_types=1);

namespace Kobens\Gemini\Exchange\Order\Maker;

use Kobens\Gemini\Exchange\Order\Fee\ApiMakerHoldBpsInterface;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Multiply;

final class RequiredQuote
{
    private ApiMakerHoldBpsInterface $apiMakerHoldBps;

    public function __construct(
        ApiMakerHoldBpsInterface $apiMakerHoldBps
    ) {
        $this->apiMakerHoldBps = $apiMakerHoldBps;
    }

    /**
     * Return the quote amount which must be available to place
     * a maker order on the order book. This takes into account
     * the deposit amount Gemini will hold beyond the expected
     * maker fee for the order.
     *
     * @param string $price
     * @param string $amount
     * @return string
     */
    public function get(string $price, string $amount): string
    {
        $costBasis = Multiply::getResult($price, $amount);
        $deposit = Multiply::getResult($costBasis, $this->apiMakerHoldBps->get());
        return Add::getResult($costBasis, $deposit);
    }
}
