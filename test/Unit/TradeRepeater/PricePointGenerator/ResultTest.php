<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator;

use Kobens\Gemini\TradeRepeater\PricePointGenerator\Result;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testGetPricePoints(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetTotalBuyFees(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetTotalBuyFeeHold(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetBuyQuote(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetBuyBase(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetTotalSellFees(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetSellQuote(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetSellBase(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetProfitBase(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetProfitQuote(): void
    {
        $this->markTestIncomplete();
    }

    public function testGetVariableIncrementPrcent(): void
    {
        $this->assertTrue((new Result([], true))->hasVariablePriceIncrementPercent());
        $this->assertFalse((new Result([], false))->hasVariablePriceIncrementPercent());
    }
}
