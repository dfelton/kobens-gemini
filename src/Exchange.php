<?php

namespace Kobens\Gemini;

use Kobens\Exchange\AbstractExchange;
use Kobens\Exchange\Book\Keeper\KeeperInterface;
use Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper;
use Kobens\Gemini\Exchange\Pair\{BTCUSD, ETHBTC, ETHUSD, LTCBTC, LTCETH, LTCUSD, ZECBTC, ZECETH, ZECLTC, ZECUSD};
use Kobens\Gemini\Api\Param\{Amount, ClientOrderId, Price, Side, Symbol};
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder;

class Exchange extends AbstractExchange
{
    const CACHE_KEY = 'gemini';

    public function __construct()
    {
        parent::__construct(
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
        return new BookKeeper($pairKey);
    }

    public function placeOrder(string $side, string $symbol, string $amount, string $price): string
    {
        // @todo error handling
        $order = new NewOrder(
            new Side($side),
            new Symbol($this->getPair($symbol)),
            new Amount($amount),
            new Price($price),
            new ClientOrderId()
        );
        $result = \json_decode($order->getResponse()['body']);
        return $result->order_id;
    }

}

