<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\PricePointGenerator;

use Kobens\Math\BasicCalculator\Add;

final class Result
{
    /**
     * @var PricePoint[]
     */
    private array $pricePoints = [];

    private string $totalBuyFees = '0';

    private string $totalBuyFeeHold = '0';

    private string $totalBuyQuote = '0';

    private string $totalBuyBase = '0';

    private string $totalSellBase = '0';

    private string $totalSellFees = '0';

    private string $totalSellQuote = '0';

    private string $totalProfitQuote = '0';

    private string $totalProfitBase = '0';

    private bool $variableIncrementPercent;

    public function __construct(array $pricePoints, bool $variableIncrementPercent)
    {
        foreach ($pricePoints as $pricePoint) {
            if ($pricePoint instanceof PricePoint) {
                $this->add($pricePoint);
            } else {
                throw new \LogicException(\sprintf(
                    '"%s" only accepts "%s" objects.',
                    self::class,
                    PricePoint::class
                ));
            }
        }
        $this->variableIncrementPercent = $variableIncrementPercent;
    }

    private function add(PricePoint $pricePoint): void
    {
        $this->pricePoints[] = $pricePoint;

        $this->totalBuyFees     = Add::getResult($this->totalBuyFees,     $pricePoint->getBuyFee());
        $this->totalBuyFeeHold  = Add::getResult($this->totalBuyFeeHold,  $pricePoint->getBuyFeeHold());
        $this->totalBuyQuote    = Add::getResult($this->totalBuyQuote,    $pricePoint->getBuyAmountQuote());
        $this->totalBuyBase     = Add::getResult($this->totalBuyBase,     $pricePoint->getBuyAmountBase());
        $this->totalSellBase    = Add::getResult($this->totalSellBase,    $pricePoint->getSellAmountBase());
        $this->totalSellFees    = Add::getResult($this->totalSellFees,    $pricePoint->getSellFee());
        $this->totalSellQuote   = Add::getResult($this->totalSellQuote,   $pricePoint->getSellAmountQuote());
        $this->totalProfitBase  = Add::getResult($this->totalProfitBase,  $pricePoint->getProfitBase());
        $this->totalProfitQuote = Add::getResult($this->totalProfitQuote, $pricePoint->getProfitQuote());
    }

    /**
     * @return PricePoint[]
     */
    public function getPricePoints(): array
    {
        return $this->pricePoints;
    }

    public function getTotalBuyFees(): string
    {
        return $this->totalBuyFees;
    }

    public function getTotalBuyFeesHold(): string
    {
        return $this->totalBuyFeeHold;
    }

    public function getTotalBuyQuote(): string
    {
        return $this->totalBuyQuote;
    }

    public function getTotalBuyBase(): string
    {
        return $this->totalBuyBase;
    }

    public function getTotalSellBase(): string
    {
        return $this->totalSellBase;
    }

    public function getTotalSellFees(): string
    {
        return $this->totalSellFees;
    }

    public function getTotalSellQuote(): string
    {
        return $this->totalSellQuote;
    }

    public function getTotalProfitBase(): string
    {
        return $this->totalProfitBase;
    }

    public function getTotalProfitQuote(): string
    {
        return $this->totalProfitQuote;
    }

    public function hasVariablePriceIncrementPercent(): bool
    {
        return $this->variableIncrementPercent;
    }
}
