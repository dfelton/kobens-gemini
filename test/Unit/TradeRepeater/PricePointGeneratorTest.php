<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\TradeRepeater\PricePointGenerator;
use PHPUnit\Framework\TestCase;

class PricePointGeneratorTest extends TestCase
{
    public function testOrderPriceIncrementIsEnforcedOnIncrements(): void
    {
        $this->markTestIncomplete();
    }

    public function testMinOrderSizeIsEnforced(): void
    {
        $this->markTestIncomplete();
    }

    public function testOrderPriceIncrementIsEnforcedOnPriceStart(): void
    {
        $this->markTestIncomplete();
    }

    public function testPriceIncrementsCorrectly(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @dataProvider \KobensTest\Gemini\Unit\TradeRepeater\PricePointGeneratorDataProvider::priceEndsCorrectly
     * @see \KobensTest\Gemini\Unit\TradeRepeater\PricePointGeneratorDataProvider::priceEndsCorrectly()
     * @param string $expectedBuyPrice
     * @param string $expectedSellPrice
     * @param PairInterface $pair
     * @param string $buyAmount
     * @param string $priceStart
     * @param string $priceEnd
     * @param string $increment
     * @param string $sellAfterGain
     */
    public function testPriceEndsCorrectly(
        string $expectedBuyPrice,
        string $expectedSellPrice,
        PairInterface $pair,
        string $buyAmount,
        string $priceStart,
        string $priceEnd,
        string $increment,
        string $sellAfterGain
    ): void {
        $pricePoints = PricePointGenerator::get($pair, $buyAmount, $priceStart, $priceEnd, $increment, $sellAfterGain)->getPricePoints();
        /** @var \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint $last */
        $last = end($pricePoints);
        $this->assertSame($expectedBuyPrice,  $last->getBuyPrice());
        $this->assertSame($expectedSellPrice, $last->getSellPrice());
    }

    public function testSetsHasVariablePriceIncrementCorrectly(): void
    {
        $this->markTestIncomplete();
    }
}
