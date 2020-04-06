<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator;

use Kobens\Gemini\TradeRepeater\PricePointGenerator\Result;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::pricePoints
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::pricePoints()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetPricePoints(array $pricePoints): void
    {
        $this->assertSame(
            $pricePoints,
            (new Result($pricePoints, false))->getPricePoints(),
            'Do we get the same price points we passed in'
        );
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::buyFees
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::buyFees()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetTotalBuyFees(string $expected, array $pricePoints): void
    {
        $this->assertSame(
            $expected,
            (new Result($pricePoints, false))->getTotalBuyFees(),
            'Does the fees for buy orders add up correctly'
        );
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::buyFeesHold
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::buyFeesHold()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetTotalBuyFeesHold(string $expected, array $pricePoints): void
    {
        $this->assertSame(
            $expected,
            (new Result($pricePoints, false))->getTotalBuyFeesHold(),
            'Does the fees held for buy orders add up correctly'
        );
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::buyQuote
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::buyQuote()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetBuyQuote(string $expected, array $pricePoints): void
    {
        $this->assertSame(
            $expected,
            (new Result($pricePoints, false))->getTotalBuyQuote(),
            'Does the quote quantity add up correctly'
        );
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::buyBase
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::buyBase()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetBuyBase(string $expected, array $pricePoints): void
    {
        $this->assertSame(
            $expected,
            (new Result($pricePoints, false))->getTotalBuyBase(),
            'Does the base quantity add up correctly'
        );
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::sellFees
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::sellFees()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetTotalSellFees(string $expected, array $pricePoints): void
    {
        $this->assertSame(
            $expected,
            (new Result($pricePoints, false))->getTotalSellFees()
        );
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::sellQuote
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::sellQuote()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetSellQuote(string $expected, array $pricePoints): void
    {
        $this->assertSame(
            $expected,
            (new Result($pricePoints, false))->getTotalSellQuote()
        );
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::sellBase
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::sellBase()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetSellBase(string $expected, array $pricePoints): void
    {
        $this->assertSame(
            $expected,
            (new Result($pricePoints, false))->getTotalSellBase()
        );
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::profitBase
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::profitBase()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetProfitBase(string $expected, array $pricePoints): void
    {
        $this->assertSame(
            $expected,
            (new Result($pricePoints, false))->getTotalProfitBase()
        );
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::profitQuote
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\ResultDataProvider::profitQuote()
     * @param string $expected
     * @param \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint[] $pricePoints
     */
    public function testGetProfitQuote(string $expected, array $pricePoints): void
    {
        $this->assertSame(
            $expected,
            (new Result($pricePoints, false))->getTotalProfitQuote()
        );
    }

    public function testGetVariableIncrementPrcent(): void
    {
        $this->assertTrue((new Result([], true))->hasVariablePriceIncrementPercent());
        $this->assertFalse((new Result([], false))->hasVariablePriceIncrementPercent());
    }

    public function testInstantiationThrowsLogicException(): void
    {
        $this->expectException(\LogicException::class);
        new Result([null], false);
    }
}
