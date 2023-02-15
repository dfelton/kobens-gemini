<?php

declare(strict_types=1);

namespace Kobens\Gemini\Taxes;

final class SaleMeta
{

    private string $sellFee;

    private string $sellQuoteAmount;

    private string $buyFee;

    private string $buyQuoteAmount;

    private string $logAmount;

    private string $proceeds;

    private string $costBasis;

    private string $gainLoss;

    private string $buyRemaining;

    private string $sellRemaining;

    public function __construct(
        string $sellFee,
        string $sellQuoteAmount,
        string $buyFee,
        string $buyQuoteAmount,
        string $logAmount,
        string $proceeds,
        string $costBasis,
        string $gainLoss,
        string $buyRemaining,
        string $sellRemaining

    ) {
        $this->sellToLogFee = $sellFee;
        $this->sellToLogQuoteAmount = $sellQuoteAmount;
        $this->buyFee = $buyFee;
        $this->buyQuoteAmount = $buyQuoteAmount;
        $this->logAmount = $logAmount;
        $this->proceeds = $proceeds;
        $this->costBasis = $costBasis;
        $this->gainLoss = $gainLoss;
        $this->buyRemaining = $buyRemaining;
        $this->sellRemaining = $sellRemaining;
    }

    public function getSellFee(): string
    {
        return $this->sellFee;
    }

    public function getSellQuoteAmount(): string
    {
        return $this->sellQuoteAmount;
    }

    public function getBuyFee(): string
    {
        return $this->buyFee;
    }

    public function getBuyQuoteAmount(): string
    {
        return $this->buyQuoteAmount;
    }

    public function getLogAmount(): string
    {
        return $this->logAmount;
    }

    public function getProceeds(): string
    {
        return $this->proceeds;
    }

    public function getCostBasis(): string
    {
        return $this->costBasis;
    }

    public function getGainLoss(): string
    {
        return $this->gainLoss;
    }

        public function getBuyRemaining(): string
    {
        return $this->buyRemaining;
    }

    public function getSellRemaining(): string
    {
        return $this->sellRemaining;
    }
}
