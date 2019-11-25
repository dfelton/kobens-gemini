<?php

namespace Kobens\Gemini\Api\WebSocket\MarketData;

use Kobens\Exchange\Book\Keeper\KeeperInterface;

interface BookKeeperFactoryInterface
{
    public function create(string $pairSymbol): KeeperInterface;
}
