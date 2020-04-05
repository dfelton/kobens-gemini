<?php

declare(strict_types=1);

namespace Kobens\GeminiTest\Unit\Exchange\Order\Fee;

use Kobens\Gemini\Exchange\Order\Fee\MaxApiMakerBps;
use PHPUnit\Framework\TestCase;

class MaxApiMakerBpsTest extends TestCase
{
    public function testGet(): void
    {
        $this->assertSame('10', MaxApiMakerBps::get());
    }
}
