<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\PricePointGenerator;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint;
use PHPUnit\Framework\TestCase;

class PricePointGeneratorTest extends TestCase
{
    public function testOrderPriceIncrementIsEnforcedOnIncrements(): void
    {
        $this->markTestSkipped('Must enforce that order price increment is enforced on increments param.');
    }

    public function testMinOrderSizeIsEnforced(): void
    {
        $this->markTestSkipped('Must enforce that minimum order size is enforced on buy amount.');
    }

    public function testOrderPriceIncrementIsEnforcedOnPriceStart(): void
    {
        $this->markTestSkipped('Must enforce that order price increment is enforced on start price.');
    }

    public function testBasicCompositionIsCorrect(): void
    {
        $result = PricePointGenerator::get(Pair::getInstance('btcusd'), '0.00014', '2.5', '5', '2.5', '0.025', '0.000003');

        $this->assertSame('0.00028',      $result->getTotalBuyBase(),     'Failed asserting total base currency buy amount is correct.');
        $this->assertSame('0.00000105',   $result->getTotalBuyFees(),     'Failed asserting total buy fees is correct.');
        $this->assertSame('0.000003675',  $result->getTotalBuyFeesHold(), 'Failed asserting total buy fee hold is correct.');
        $this->assertSame('0.00105',      $result->getTotalBuyQuote(),    'Failed asserting total quote currency buy amount is correct.');

        $this->assertSame('0.000006',     $result->getTotalProfitBase(),  'Failed asserting total base currency profit is correct.');
        $this->assertSame('0.0000027951', $result->getTotalProfitQuote(), 'Failed asserting total quote currency profit is correct.');

        $this->assertSame('0.000274',     $result->getTotalSellBase(),  'Failed asserting total base currency sell amount is correct.');
        $this->assertSame('0.0000010549', $result->getTotalSellFees(),  'Failed asserting total sell fee is correct.');
        $this->assertSame('0.0010549',    $result->getTotalSellQuote(), 'Failed asserting total quote currency sell amount is correct');

        $this->assertSame(
            2,
            count($result->getPricePoints()),
            'Failed asserting price point generator yielded expected number of individual price points'
        );

        $pricePoints = $result->getPricePoints();
        $first = \reset($pricePoints);
        if ($first instanceof PricePoint) {
            $this->assertSame('0.00014',     $first->getBuyAmountBase());
            $this->assertSame('0.00035',     $first->getBuyAmountQuote());
            $this->assertSame('0.00000035',  $first->getBuyFee());
            $this->assertSame('0.000001225', $first->getBuyFeeHold());
            $this->assertSame('2.5',         $first->getBuyPrice());

            $this->assertSame('0.000137',      $first->getSellAmountBase());
            $this->assertSame('0.00035209',    $first->getSellAmountQuote());
            $this->assertSame('0.00000035209', $first->getSellFee());
            $this->assertSame('2.57',          $first->getSellPrice());

            $this->assertSame('0.000003',      $first->getProfitBase());
            $this->assertSame('0.00000138791', $first->getProfitQuote());

        } else {
            $this->fail('Failed fetching first price point.');
        }
        $last = \end($pricePoints);
        if ($last instanceof PricePoint) {
            $this->assertSame('0.00014',    $last->getBuyAmountBase());
            $this->assertSame('0.0007',     $last->getBuyAmountQuote());
            $this->assertSame('0.0000007',  $last->getBuyFee());
            $this->assertSame('0.00000245', $last->getBuyFeeHold());
            $this->assertSame('5',          $last->getBuyPrice());

            $this->assertSame('0.000137',      $last->getSellAmountBase());
            $this->assertSame('0.00070281',    $last->getSellAmountQuote());
            $this->assertSame('0.00000070281', $last->getSellFee());
            $this->assertSame('5.13',          $last->getSellPrice());

            $this->assertSame('0.000003',      $last->getProfitBase());
            $this->assertSame('0.00000140719', $last->getProfitQuote());
        } else {
            $this->fail('Failed fetching last price point.');
        }
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
        /** @var PricePoint $last */
        $last = \end($pricePoints);
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
