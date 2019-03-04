<?php

namespace Kobens\Gemini;

use Kobens\Exchange\AbstractExchange;
use Kobens\Exchange\Book\Keeper\KeeperInterface;
use Kobens\Gemini\Exchange\Pair\{BTCUSD, ETHBTC, ETHUSD, LTCBTC, LTCETH, LTCUSD, ZECBTC, ZECETH, ZECLTC, ZECUSD};
use Zend\Cache\Storage\StorageInterface;

class Exchange extends AbstractExchange
{
    const CACHE_KEY = 'gemini';

    public function __construct(StorageInterface $cacheInterface)
    {
        parent::__construct($cacheInterface, [
            new BTCUSD(),
            new ETHBTC(),
            new ETHUSD(),
            new LTCBTC(),
            new LTCETH(),
            new LTCUSD(),
            new ZECBTC(),
            new ZECETH(),
            new ZECLTC(),
            new ZECUSD(),
        ]);
    }

    public function getCacheKey(): string
    {
        return self::CACHE_KEY;
    }

    public function getBookKeeper(string $pairKey): KeeperInterface
    {
        return new \Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper($this, $pairKey);
    }

}

