<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Stats\DailyProfit;

use Kobens\Gemini\TradeRepeater\Stats\ProcessorInterface;
use Zend\Db\Adapter\Adapter;

final class Processor implements ProcessorInterface
{
    private Adapter $adapter;

    public function __construct()
    {
    }

    public function execute(): void
    {
    }
}
