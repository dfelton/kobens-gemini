<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount;

use Kobens\Exchange\PairInterface;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Add;

final class Calculator implements CalculatorInterface
{
    /**
     * @var
     */
    private string $rate;

    private string $originalQuoteAmount;

    private PairInterface $pair;

    private string $baseAmount;

    private string $quoteAmount;

    private string $quoteMaxTierMakerTransactionFee;

    private string $quoteMakerFeeDeposit;

    private string $quoteRemaining;


    /**
     * Using the specified quote amount, how much base currency can we place
     * an order for (excluding minimum order rules, but obeying minimum order
     * increment rules). While taking into consideration fees, both what will
     * be incurred at time of transaction, as well as additional fees that
     * Gemini holds (which happens to be equal to their tier nax taker fees
     * at time of writing).
     *
     * @param PairInterface $pair
     * @param string $quoteAmount
     * @return string
     */
    public function __construct(PairInterface $pair, string $quoteAmount, string $rate)
    {
        $this->pair = $pair;
        $this->originalQuoteAmount = $quoteAmount;
        $this->rate = $rate;
        $this->init();
    }

    private function init(): void
    {
        // 1. find (base) amount if we were to ignore deposit fees
        // 2. with that much (quote) amount, how much would deposit fees be
        //    (knowing that puts us over allowed amount)
        // 3. find out base amount, equal to the amount of quote amount we are
        //    over to the allowed
        // 4. subtract that base amount, to the original base amount we determined
        //    in step 1 above. We now have a starting point to use.
        // 5. With the value we acquired in step 4, now incrementally increase until
        //    we reach maximum amount possible as per the allowed quote amount,
        //    with deposit fees, adhering to minimum increment amounts on the pair.

        $baseAmount = bcdiv(
            $this->originalQuoteAmount,
            $this->rate,
            $this->getScaleOfBaseOrderIncrementsFromPair()
        );
        $quoteAmountExact = Multiply::getResult($baseAmount, $this->rate);
        $depositFee = Multiply::getResult($quoteAmountExact, '0.0035');
        $baseAmountInDepositFee = bcdiv(
            $depositFee,
            $this->rate,
            $this->getScaleOfBaseOrderIncrementsFromPair()
        );

        $baseAmount = Subtract::getResult($baseAmount, $baseAmountInDepositFee);
        $lastResult = $result = $this->getResult($baseAmount);

        $i = 0;

        while (Compare::getResult($result['quote_amount_remaining'], '0') === Compare::LEFT_GREATER_THAN) {
            $lastResult = $result;

            // FIXME: This is terribly slow and computationally expensive.
            // We could improve this by appending 5 to the current $baseAmount.
            // If this causes us to go over, we undo that, and append 4, then 3, etc,
            // until no longer over or 0.

            // Same in reverse... if appending 5 didn't cause us to go over, we remove it
            // and append 6, 7, etc until over or we reach 9.

            // At such a point, we then append 5 again and repeat process over again until
            // we've reached maximum precision allowed for order increments.

            // Example: Start value (BTC: 0.125)
            // Possible Tests:
            //      0.1255 (Caused us to go over, start reducing)
            //      0.1254
            //      0.1253 (we're now under again, append 5 once again)
            //      0.12535
            //      0.12536 // (This caused us to go over, use last value and append 5 again)
            //      0.125355
            //      repeat...
            //      0.12535536 // we now reached maximum precision on this pair without going over

            $baseAmount = Add::getResult($baseAmount, $this->pair->getMinOrderIncrement());
            $result = $this->getResult($baseAmount);

            if (++$i > 100000000) {
                throw new \Exception ('Maximum Iterations Reached.');
            }
        }
        $this->baseAmount = $lastResult['base_amount'];
        $this->quoteAmount = $lastResult['quote_amount'];
        $this->quoteRemaining = $lastResult['quote_amount_remaining'];
        $this->quoteMakerFeeDeposit = $lastResult['deposit_fee'];
        $this->quoteMaxTierMakerTransactionFee = $lastResult['quote_max_maker_fee'];
    }

    private function getResult(string $baseAmount): array
    {
        $quoteAmount = Multiply::getResult($this->rate, $baseAmount);
        $depositFee = Multiply::getResult($quoteAmount, '0.0035');
        $quoteAmountRemaining = Subtract::getResult(
            Subtract::getResult(
                $this->originalQuoteAmount,
                $quoteAmount
            ),
            $depositFee
        );

        return [
            'base_amount' => $baseAmount,
            'deposit_fee' => $depositFee,
            'quote_max_maker_fee' => Multiply::getResult($quoteAmount, '0.001'),
            'quote_amount' => $quoteAmount,
            'quote_amount_remaining' => $quoteAmountRemaining,
        ];
    }

    private function getScaleOfBaseOrderIncrementsFromPair(): int
    {
        $minIncrementParts = explode('.', $this->pair->getMinOrderIncrement());
        return ($minIncrementParts[1] ?? null) === null ? 0 : strlen($minIncrementParts[1]);
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function getOriginalQuoteAmount(): string
    {
        return $this->originalQuoteAmount;
    }

    public function getPair(): PairInterface
    {
        return $this->pair;
    }

    public function getBaseAmount(): string
    {
        return $this->baseAmount;
    }

    public function getQuoteAmount(): string
    {
        return $this->quoteAmount;
    }

    public function getQuoteMaxTierMakerTransactionFee(): string
    {
        return $this->quoteMaxTierMakerTransactionFee;
    }

    public function getQuoteMakerFeeDeposit(): string
    {
        return $this->quoteMakerFeeDeposit;
    }

    public function getQuoteRemaining(): string
    {
        return $this->quoteRemaining;
    }
}
