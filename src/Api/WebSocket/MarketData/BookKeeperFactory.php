<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\WebSocket\MarketData;

use Kobens\Exchange\ExchangeInterface;
use Kobens\Exchange\Book\Keeper\KeeperInterface;
use Zend\Cache\Storage\StorageInterface;

final class BookKeeperFactory implements BookKeeperFactoryInterface
{
    /**
     * @var ExchangeInterface
     */
    private $exchange;

    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(
        ExchangeInterface $exchangeInterface,
        StorageInterface $storageInterface
    ) {
        $this->exchange = $exchangeInterface;
        $this->storage = $storageInterface;
    }

    public function create(string $pairSymbol): KeeperInterface
    {
        return new BookKeeper($this->storage, $this->exchange, $pairSymbol);
    }
}
