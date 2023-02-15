<?php

declare(strict_types=1);

namespace Kobens\Gemini\Taxes;

use Kobens\Gemini\Exchange\Order\Fee\Trade\BPS;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Subtract;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Add;

final class SaleMetaFactory
{
    private BPS $bps;

    public function __construct(
        BPS $bps
    ) {
        $this->bps = $bps;
    }

    public function get(string $sellRemaining, array $buy, array $sell): SaleMeta
    {
        switch (Compare::getResult($sellRemaining, $buy['amount_remaining'])) {
            case Compare::RIGHT_GREATER_THAN: // buy is larger than sell remaining
                $logAmount = $sellRemaining;
                $buyRemaining = Subtract::getResult($buy['amount_remaining'], $sellRemaining);
                $sellRemaining = '0';
                break;

            case Compare::EQUAL: // they are equal
                $logAmount = $sellRemaining;
                $buyRemaining = '0';
                $sellRemaining = '0';
                break;

            case Compare::LEFT_GREATER_THAN: // sell remaining is larger than buy
                $logAmount = $buy['amount_remaining'];
                $buyRemaining = '0';
                $sellRemaining = Subtract::getResult($sellRemaining, $buy['amount_remaining']);
                break;

            default:
                throw \ErrorException();
        }

        // FIXME: Handle Calculating Fee Correctly
        // This try/catch is to handle transactions nov-dev 2021, when gemini
        // no longer calculated fees to its exact precision and instead started
        // truncating the end of the value at 6 decimals or so.
        // (exact length varies depending on trading pair)
        $sellToLogQuoteAmount = Multiply::getResult($logAmount, $sell['price']);
        if ($sell['fee_amount'] === '0.00') {
            $sellToLogFee = '0.00';
        } else {
            try {
                $sellFeePercent = $this->bps->getRate($sell['amount'], $sell['price'], $sell['fee_amount']);
            } catch (\LogicException $e) {
                $sellFeePercent = '0.001';
            }
            $sellToLogFee = Multiply::getResult($sellToLogQuoteAmount, $sellFeePercent);
        }

        $buyToLogQuoteAmount = Multiply::getResult($logAmount, $buy['price']);
        if ($buy['fee_amount'] === '0.00') {
            $buyToLogFee = '0.00';
        } else {
            try {
                $buyFeePercent = $this->bps->getRate($buy['amount'], $buy['price'], $buy['fee_amount']);
            } catch (\LogicException $e) {
                $buyFeePercent = '0.001';
            }
            $buyToLogFee = Multiply::getResult($buyToLogQuoteAmount, $buyFeePercent);
        }

        $costBasis = Add::getResult($buyToLogQuoteAmount, $buyToLogFee);
        $proceeds = Subtract::getResult($sellToLogQuoteAmount, $sellToLogFee);
        $capitalGain = Subtract::getResult($proceeds, $costBasis);

        $this->validateBuyToSell(
            $logAmount,
            $costBasis,
            $proceeds,
            $sellToLogFee,
            $buyToLogFee,
            $sellRemaining,
            $buyRemaining
        );

        return new SaleMeta(
            $sellToLogFee,
            $sellToLogQuoteAmount,
            $buyToLogFee,
            $buyToLogQuoteAmount,
            $logAmount,
            $proceeds,
            $costBasis,
            $capitalGain,
            $buyRemaining,
            $sellRemaining
        );
    }

    private function validateBuyToSell(
        string $logAmount,
        string $costBasis,
        string $proceeds,
        string $sellToLogFee,
        string $buyToLogFee,
        string $sellRemaining,
        string $buyRemaining
    ): void {
        if (Compare::getResult($logAmount, '0') === Compare::RIGHT_GREATER_THAN) {
            throw new \LogicException('Negative Log Amount');
        }
        if (Compare::getResult($costBasis, '0') === Compare::RIGHT_GREATER_THAN) {
            throw new \LogicException('Negative Cost Basis');
        }
        if (Compare::getResult($proceeds, '0') === Compare::RIGHT_GREATER_THAN) {
            throw new \LogicException('Negative Proceeds');
        }
        if (Compare::getResult($sellToLogFee, '0') === Compare::RIGHT_GREATER_THAN) {
            throw new \LogicException('Negative Sell Fee');
        }
        if (Compare::getResult($buyToLogFee, '0') === Compare::RIGHT_GREATER_THAN) {
            throw new \LogicException('Negative Buy Fee');
        }
        if (Compare::getResult($sellRemaining, '0') === Compare::RIGHT_GREATER_THAN) {
            throw new \LogicException('Negative Sell Remaining Amount');
        }
        if (Compare::getResult($buyRemaining, '0') === Compare::RIGHT_GREATER_THAN) {
            throw new \LogicException('Negative Buy Remaining Amount');
        }
    }
}
