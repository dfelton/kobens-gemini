<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\PricePointGenerator;

use Kobens\Gemini\Exchange\Order\Fee\GetPercent;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;

/**
 * TODO: would nice to have a fee side parameter (defaulted to quote) and have fees be capable of originating from base if needed.
 * TODO: Reconsider where this belongs... maybe Kobens\Currency? -- But it is dependant on GetPercent
 */
final class PricePoint
{
    private string $buyPrice;

    private string $buyAmount;

    private string $sellPrice;

    private string $sellAmount;

    private string $bps;

    private string $holdBps;

    /**
     * PricePoint constructor.
     *
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     * @param string $holdBps
     */
    public function __construct(
        string $buyAmount,
        string $buyPrice,
        string $sellAmount,
        string $sellPrice,
        string $bps,
        string $holdBps
    ) {
        $this->buyAmount  = $buyAmount;
        $this->buyPrice   = $buyPrice;
        $this->sellAmount = $sellAmount;
        $this->sellPrice  = $sellPrice;
        $this->bps        = $bps;
        $this->holdBps    = $holdBps;
    }

    /**
     * @return string
     */
    public function getBuyPrice(): string
    {
        return $this->buyPrice;
    }

    /**
     * @return string
     */
    public function getBuyAmountBase(): string
    {
        return $this->buyAmount;
    }

    /**
     * @return string
     */
    public function getBuyAmountQuote(): string
    {
        return Multiply::getResult($this->buyAmount, $this->buyPrice);
    }

    /**
     * @return string
     */
    public function getBuyFee(): string
    {
        return Multiply::getResult($this->getBuyAmountQuote(), GetPercent::get($this->bps));
    }

    /**
     * @return string
     */
    public function getBuyFeeHold(): string
    {
        return Multiply::getResult($this->getBuyAmountQuote(), GetPercent::get($this->holdBps));
    }

    /**
     * @return string
     */
    public function getSellPrice(): string
    {
        return $this->sellPrice;
    }

    /**
     * @return string
     */
    public function getSellAmountBase(): string
    {
        return $this->sellAmount;
    }

    /**
     * @return string
     */
    public function getSellAmountQuote(): string
    {
        return Multiply::getResult($this->sellPrice, $this->sellAmount);
    }

    /**
     * @return string
     */
    public function getSellFee(): string
    {
        return Multiply::getResult($this->getSellAmountQuote(), GetPercent::get($this->bps));
    }

    /**
     * @return string
     */
    public function getProfitBase(): string
    {
        return Subtract::getResult($this->getBuyAmountBase(), $this->getSellAmountBase());
    }

    /**
     * @return string
     */
    public function getProfitQuote(): string
    {
        return Subtract::getResult(
            Subtract::getResult($this->getSellAmountQuote(), $this->getSellFee()),
            Add::getResult($this->getBuyAmountQuote(), $this->getBuyFee())
        );
    }
}
