<?php

declare(strict_types=1);

namespace Kobens\GeminiTest\Unit\Exchange\Order\Fee;

use Kobens\Gemini\Exchange\Order\Fee\ApiMakerHoldBps;
use PHPUnit\Framework\TestCase;

class ApiMakerHoldBpsTest extends TestCase
{
    public function testGet(): void
    {
        $this->assertSame('35', ApiMakerHoldBps::get());
    }
}
