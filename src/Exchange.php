<?php

namespace Kobens\Gemini;

use Kobens\Exchange\AbstractExchange;
use Kobens\Exchange\Book\Keeper\KeeperInterface;
use Kobens\Exchange\Order\StatusInterface;
use Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\Exchange\Order\Status;

final class Exchange extends AbstractExchange
{
    private const CACHE_KEY = 'gemini';

    public function __construct()
    {
        parent::__construct(Pair::getAllInstances());
    }

    public function getCacheKey(): string
    {
        return self::CACHE_KEY;
    }

    public function getBookKeeper(string $pairKey): KeeperInterface
    {
        return new BookKeeper($pairKey);
    }

    /**
     * @deprecated
     * @param string $side
     * @param string $symbol
     * @param string $amount
     * @param string $price
     * @return string
     * @throws \Kobens\Exchange\Exception\Exception
     * @throws \Exception
     */
    public function placeOrder(string $side, string $symbol, string $amount, string $price): string
    {
        throw new \Exception(__METHOD__.' is currently deprecated.');
        return '';
    }

    public function getActiveOrderIds(): array
    {
        throw new \Exception(__METHOD__.' is currently deprecated.');
        return [];
    }

    public function getOrderMetaData(string $orderId): array
    {
        throw new \Exception(__METHOD__.' is currently deprecated.');
        return [];
    }

    public function getStatusInterface(): StatusInterface
    {
        return new Status();
    }
}
