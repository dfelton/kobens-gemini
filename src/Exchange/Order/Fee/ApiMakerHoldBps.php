<?php

declare(strict_types=1);

namespace Kobens\Gemini\Exchange\Order\Fee;

final class ApiMakerHoldBps
{
    /**
     * The BPS the exchange uses for a hold placed on the account
     * while a buy order is resting on the order books (maker).
     *
     * This may differ from the BPS used as the fee tier for when
     * portion(s) of the order is actually executed.
     *
     * This is not advertised anywhere in their API docs. This was
     * discovered through manual observations while interacting with
     * their API.
     *
     * Assumption (not confirmed) on Gemini's logic is they put a hold
     * on the order using the highest BPS available for Taker orders,
     * of the highest fee tier the exchange has at the time.
     *
     * This way, if fee structures are changed in the future, resting
     * orders will most likely already have enough quote currency held
     * to cover the fees (in the event of a fee structure change yielding
     * a BPS increase). However this is not confirmed. This also raises
     * the question, what would happen to resting orders in the event that
     * the fee structure were to ever change in such a way that the fees
     * for an order are beyond the amount held by the initial order placement.
     *
     * Example:
     *   At Time of Order Placement:
     *      - Hold BPS is 35 for Maker API placed orders (0.350%)
     *      - Highest BPS (lowest trading volume fee tier) is 10 for Maker API placed orders
     *   After Fee Change:
     *      - Hold BPS is 150 for Maker API placed orders (1.500%)
     *      - Highest BPS tier is 100 (1.000%) for Maker API placed orders
     *
     * While this drastic of a change may seem unlikely, there was indeed
     * a timeframe where Gemini's fee structure was this high.
     */
    private const API_MAKER_HOLD_BPS = '35';

    private function __construct()
    {
    }

    public static function get(): string
    {
        return self::API_MAKER_HOLD_BPS;
    }
}
