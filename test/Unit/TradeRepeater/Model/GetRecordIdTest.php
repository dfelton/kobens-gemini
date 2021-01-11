<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater\Model;

use Kobens\Gemini\TradeRepeater\Model\GetRecordId;
use PHPUnit\Framework\TestCase;

class GetRecordIdTest extends TestCase
{
    private ?GetRecordId $getRecordId = null;

    protected function setUp(): void
    {
        $this->getRecordId = new GetRecordId();
    }

    protected function tearDown(): void
    {
        $this->getRecordId = null;
    }

    public function testGetReturnsId(): void
    {
        $this->assertSame(1, $this->getRecordId->get('repeater_1_sell_1610309695.9983'));
        $this->assertSame(2, $this->getRecordId->get('repeater_2_sell_1610328861.0035'));
        $this->assertSame(299, $this->getRecordId->get('repeater_299_buy_1610328918.5095'));
        $this->assertSame(1234, $this->getRecordId->get('repeater_1234_buy_1610328961.6939'));
        $this->assertSame(100, $this->getRecordId->get('repeater_100'));
    }

    /**
     * @dataProvider dataProviderInvalidFormat
     */
    public function testGetThrowsInvalidArgumentException(string $clientOrderId, $message): void
    {
        $this->expectExceptionObject(new \InvalidArgumentException($message));
        $this->getRecordId->get($clientOrderId);
    }

    public function dataProviderInvalidFormat(): array
    {
        return [
            ['foo', 'Client Order ID "foo" does is not a valid TradeRepeater format.'],
            ['repeater1234_sell_1610321924.6922', 'Client Order ID "repeater1234_sell_1610321924.6922" does is not a valid TradeRepeater format.'],
        ];
    }
}
