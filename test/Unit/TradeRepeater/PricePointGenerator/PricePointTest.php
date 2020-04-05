<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator;

use Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class PricePointTest extends TestCase
{
    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::buyPrice()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::buyPrice()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetBuyPrice(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getBuyPrice());
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::buyAmountBase()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::buyAmountBase()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetBuyAmountBase(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getBuyAmountBase());
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::buyAmountQuote()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::buyAmountQuote()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetBuyAmountQuote(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getBuyAmountQuote());
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::buyFee()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::buyFee()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetBuyFee(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getBuyFee());
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::sellPrice()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::sellPrice()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetSellPrice(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getSellPrice());
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::sellAmountBase()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::sellAmountBase()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetSellAmountBase(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getSellAmountBase());
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::sellAmountQuote()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::sellAmountQuote()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetSellAmountQuote(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getSellAmountQuote());
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::sellFee()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::sellFee()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetSellFee(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getSellFee());
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::profitBase()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::profitBase()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetProfitBase(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getProfitBase());
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::profitQuote()
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGenerator\PricePointDataProvider::profitQuote()
     * @param string $expected
     * @param string $buyAmount
     * @param string $buyPrice
     * @param string $sellAmount
     * @param string $sellPrice
     * @param string $bps
     */
    public function testGetProfitQuote(string $expected, string $buyAmount, string $buyPrice, string $sellAmount, string $sellPrice, string $bps): void
    {
        $pricePoint = new PricePoint($buyAmount, $buyPrice, $sellAmount, $sellPrice, $bps);
        $this->assertSame($expected, $pricePoint->getProfitQuote());
    }
}
