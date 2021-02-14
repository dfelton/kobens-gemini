<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Stats;

interface ProcessorInterface
{
    public function execute(): void;
}
