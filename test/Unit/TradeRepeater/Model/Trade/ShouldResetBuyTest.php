<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater\Model\Trade;

use PHPUnit\Framework\TestCase;
use Kobens\Gemini\TradeRepeater\Model\Trade\ShouldResetBuy;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Api\Market\GetPriceInterface;

class ShouldResetBuyTest extends TestCase
{
    private ?ShouldResetBuy $shouldReset = null;

    private ?GetPriceInterface $getPrice = null;

    private ?OrderStatusInterface $orderStatus = null;

    protected function setUp(): void
    {
        $this->orderStatus = $this->createStub(OrderStatusInterface::class);
        $this->getPrice = $this->createStub(GetPriceInterface::class);
        $this->shouldReset = new ShouldResetBuy($this->orderStatus, $this->getPrice);
    }

    protected function tearDown(): void
    {
        $this->shouldReset = null;
        $this->getPrice = null;
        $this->orderStatus = null;
    }

    /**
     * @dataProvider dataProviderTestGetReturnsTrue
     *
     * @param Trade $trade                              Dummy Trade Record
     * @param string $currentBidPrice                   Mocked out current quote price for bidding on the asset
     */
    public function testGetReturnsTrue(Trade $trade, string $currentBidPrice): void
    {
        $this->getPrice->method('getBid')->willReturn($currentBidPrice);
        $this->orderStatus->method('getStatus')->willReturn(json_decode('{"executed_amount":"0"}'));
        $this->assertTrue($this->shouldReset->get($trade));
    }

    public function dataProviderTestGetReturnsTrue(): array
    {
        return [
            [
                new Trade(
                    null,
                    1,
                    0,
                    'BUY_PLACED',
                    'linkusd',
                    '0.1',
                    '12',
                    '0.1',
                    '12.6',
                    'repeater_1_buy_1610330096.6266',
                    1234,
                    'repeater_1_sell_1610331096.6266',
                    5678,
                    null,
                    '{"buy_price":"11.00"}',
                    '2021-01-10 19:54:56'
                ),
                '20'
            ],
        ];
    }

    /**
     * @dataProvider dataProviderTestGetReturnsFalse
     *
     * @param Trade $trade                              Dummy Trade Record
     * @param string $currentBidPrice                   Mocked out current quote price for bidding on the asset
     */
    public function testGetReturnsFalse(Trade $trade, string $currentBidPrice, string $executedAmount): void
    {
        $this->getPrice->method('getBid')->willReturn($currentBidPrice);
        $this->orderStatus->method('getStatus')->willReturn(json_decode('{"executed_amount":"' . $executedAmount . '"}'));
        $this->assertFalse($this->shouldReset->get($trade));
    }

    /**
     * TODO: Add scenarios for the following:
     *  - minimum age threshold is too low
     *  - spread between the market's current bid price, and out bid price, is too low
     * With each scenario above, in order to properly test, make sure that is the only case causing
     * a false result for the test case.
     *
     * @return array
     */
    public function dataProviderTestGetReturnsFalse(): array
    {
        return [
            [
                new Trade(
                    null,
                    1,
                    0,
                    'BUY_PLACED',
                    'linkusd',
                    '0.1',
                    '12',
                    '0.1',
                    '12.6',
                    'repeater_1_buy_1610330096.6266',
                    1234,
                    'repeater_1_sell_1610331096.6266',
                    5678,
                    null,
                    '{"buy_price":"12.00"}',
                    '2021-01-10 19:54:56'
                ),
                '20',
                '0'
            ],
            [
                new Trade(
                    null,
                    1,
                    0,
                    'BUY_PLACED',
                    'linkusd',
                    '0.1',
                    '12',
                    '0.1',
                    '12.6',
                    'repeater_1_buy_1610330096.6266',
                    1234,
                    'repeater_1_sell_1610331096.6266',
                    5678,
                    null,
                    '{"buy_price":"11.59"}',
                    '2021-01-10 19:54:56'
                ),
                '20',
                '0.001'
            ],
        ];
    }
}
