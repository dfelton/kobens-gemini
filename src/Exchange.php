<?php

namespace Kobens\Gemini;

use Kobens\Exchange\AbstractExchange;
use Kobens\Exchange\Book\Keeper\KeeperInterface;
use Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper;
use Kobens\Gemini\Exchange\Pair\{BTCUSD, ETHBTC, ETHUSD, LTCBTC, LTCETH, LTCUSD, ZECBTC, ZECETH, ZECLTC, ZECUSD};
use Zend\Cache\Storage\StorageInterface;
use Kobens\Gemini\Api\Key;
use Zend\Config\Config;

class Exchange extends AbstractExchange
{
    const CACHE_KEY = 'gemini';

    /**
     * @var Key
     */
    protected $key;

    public function __construct(
        StorageInterface $cacheInterface,
        Config $keyConfig
    )
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
        $this->key = new Key($keyConfig);
    }

    public function getCacheKey(): string
    {
        return self::CACHE_KEY;
    }

    public function getBookKeeper(string $pairKey): KeeperInterface
    {
        return new BookKeeper($this, $pairKey, $this->key->getHost());
    }

}

