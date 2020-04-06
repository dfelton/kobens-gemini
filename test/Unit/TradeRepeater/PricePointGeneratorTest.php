<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
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
        $pricePoints = PricePointGenerator::get(Pair::getInstance('btcusd'), '1', '2.5', '10', '2.5', '0.025')->getPricePoints();
        $buyPrices = ['2.5', '5', '7.5', '10'];
        $sellPrices = ['2.57', '5.13', '7.69', '10.25'];
        foreach ($pricePoints as $i => $pricePoint) {
            $this->assertSame($buyPrices[$i], $pricePoint->getBuyPrice());
            $this->assertSame($sellPrices[$i], $pricePoint->getSellPrice());
        }
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
        $this->assertSame(
            true,
            PricePointGenerator::get(Pair::getInstance('btcusd'), '1', '2.5', '10', '2.5', '0.025')->hasVariablePriceIncrementPercent()
        );
        $this->assertSame(
            false,
            PricePointGenerator::get(Pair::getInstance('btcusd'), '1', '10', '100', '10', '0.025')->hasVariablePriceIncrementPercent()
        );
    }
}
