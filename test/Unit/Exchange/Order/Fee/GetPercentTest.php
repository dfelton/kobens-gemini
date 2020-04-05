<?php

declare(strict_types=1);

namespace Kobens\GeminiTest\Unit\Exchange\Order\Fee;

use Kobens\Gemini\Exchange\Order\Fee\GetPercent;
use PHPUnit\Framework\TestCase;

class GetPercentTest extends TestCase
{
    /**
     */
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
            ['0.35',  '35'],
            ['0.25',  '25'],
            ['0.2',   '20'],
            ['0.15',  '15'],
            ['0.1',   '10'],
            ['0.125', '12.5'],
            ['0.075',  '7.5'],
            ['0.05',   '5'],
            ['0.04',   '4'],
            ['0.03',   '3'],
        ];
    }
}
