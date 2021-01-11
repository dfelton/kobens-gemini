<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Math\PercentDifference;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Gemini\Api\Market\GetPriceInterface;

final class ShouldResetBuy implements ShouldResetBuyInterface
{
    /**
     * @var OrderStatusInterface
     */
    private OrderStatusInterface $orderStatus;

    /**
     * @var GetPriceInterface
     */
    private GetPriceInterface $getPrice;

    /**
     * Minimum age in seconds an order must be to be valid for a reset.
     *
     * @var int
     */
    private int $minAge;

    /**
     * Minimum spread (percentage wise) from the current prices an order
     * must to be valid for a reset.
     *
     * '1' equates to 1% difference, '2' equates to 2%, etc.
     *
     * Supports fractions of a percent up to any precision level. For example
     * '1.12345' would accurately enforce 1.12345% percent.
     *
     * @var string
     */
    private string $minSpread;

    /**
     * TODO: add private setMinAge() and setMinSpread() functions that validate input and
     * throw exception on invalid arguments.
     *
     * @param OrderStatusInterface $orderStatus
     * @param int $minAge
     * @param string $minSpread
     */
    public function __construct(
        OrderStatusInterface $orderStatus,
        GetPriceInterface $getPrice,
        int $minAge = 1800,
        string $minSpread = '2'
    ) {
        $this->orderStatus = $orderStatus;
        $this->getPrice = $getPrice;
        $this->minAge = $minAge;
        $this->minSpread = $minSpread;
    }

    /**
     * @param Trade $trade
     * @return bool
     */
    public function get(Trade $trade): bool
    {
        $meta = \json_decode($trade->getMeta());
        return
            Compare::getResult($trade->getBuyPrice(), $meta->buy_price) !== Compare::EQUAL &&
            \time() - \strtotime($trade->getUpdatedAt()) > $this->minAge &&
            Compare::getResult(
                PercentDifference::getResult($meta->buy_price, $this->getPrice->getBid($trade->getSymbol())),
                $this->minSpread
            ) === Compare::LEFT_GREATER_THAN &&
            Compare::getResult($this->orderStatus->getStatus($trade->getBuyOrderId())->executed_amount, '0') === Compare::EQUAL;
    }
}
