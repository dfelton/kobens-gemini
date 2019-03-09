<?php

namespace Kobens\Gemini;

use Kobens\Exchange\AbstractExchange;
use Kobens\Exchange\Book\Keeper\KeeperInterface;
use Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper;
use Kobens\Gemini\Exchange\Pair\{BTCUSD, ETHBTC, ETHUSD, LTCBTC, LTCETH, LTCUSD, ZECBTC, ZECETH, ZECLTC, ZECUSD};
use Zend\Cache\Storage\StorageInterface;
use Kobens\Gemini\App\Cache;

class Exchange extends AbstractExchange
{
    const CACHE_KEY = 'gemini';

    public function __construct()
    {
        parent::__construct(
            (new Cache())->getCache(),
            [
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
            ]
        );
    }

    public function getCacheKey(): string
    {
        return self::CACHE_KEY;
    }

    public function getBookKeeper(string $pairKey): KeeperInterface
    {
        return new BookKeeper($this, $pairKey);
    }

}

