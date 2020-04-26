<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\WebSocket\MarketData;

use Kobens\Exchange\Book\Keeper\KeeperInterface;

interface BookKeeperFactoryInterface
{
    public function create(string $pairSymbol): KeeperInterface;
}
