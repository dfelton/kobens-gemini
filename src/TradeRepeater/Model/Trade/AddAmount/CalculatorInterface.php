<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount;

use Kobens\Exchange\PairInterface;

interface CalculatorInterface
{
    public function getRate(): string;

    public function getOriginalQuoteAmount(): string;

    public function getPair(): PairInterface;

    /**
     * How much base currency can we place an order for.
     *
     * - Excluding minimum order rules
     * - Obeying minimum order increment rules.
     * - Including Fees (both transaction and deposits returned to available balance after order)
     *
     * @return string
     */
    public function getBaseAmount(): string;

    /**
     * Quote amount to be used, from the amount which was available.
     *
     * @return string
     */
    public function getQuoteAmount(): string;

    /**
     * Quote amount remaining, from the amount which was available.
     *
     * @return string
     */
    public function getQuoteRemaining(): string;

    /**
     * What is the transaction fee for a maker order of an account
     * which is in the of the lowest trading volume, in regards
     * to the tiered fee structure.
     *
     *
     * @return string
     */
    public function getQuoteMaxTierMakerTransactionFee(): string;

    /**
     * What is the total quote amount to be held from available
     * balances while the buy order is on the order books.
     *
     * Gemini appears to hold the amount equal to the maximum taker
     * fee, as opposed to the maximum maker tier for orders.
     *
     * The difference from the deposit amount to transaction
     * fee amount that occurrs at the time of transaction, is returned
     * back to the available balance after the order is no longer resting
     * on the order books.
     *
     * Unsure if all the left over deposit amount is returned at time order
     * is no longer live. OR is it returned at time of individual transactions,
     * in amounts porportional to the size of the order as a whole,
     * for orders which incurr multiple transactions to fill.
     *
     * @return string
     */
    public function getQuoteMakerFeeDeposit(): string;
}
