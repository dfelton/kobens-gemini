<?php

declare(strict_types=1);

namespace Kobens\GeminiTest\Unit\Exchange\Order\Fee;

use Kobens\Gemini\Exchange\Order\Fee\GetPercent;
use PHPUnit\Framework\TestCase;

class GetPercentTest extends TestCase
{
    /**
     * @dataProvider dataSet()
     * @param string $expected
     * @param string $bps
     */
    public function testGet(string $expected, string $bps): void
    {
        $this->assertEquals($expected, GetPercent::get($bps));
    }

    public function dataSet(): array
    {
        return [
            ['0.01',   '100'],
            ['0.0035',  '35'],
            ['0.0025',  '25'],
            ['0.002',   '20'],
            ['0.0015',  '15'],
            ['0.001',   '10'],
            ['0.00125', '12.5'],
            ['0.00075',  '7.5'],
            ['0.0005',   '5'],
            ['0.0004',   '4'],
            ['0.0003',   '3'],
        ];
    }
}
