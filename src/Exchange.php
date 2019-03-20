<?php

namespace Kobens\Gemini;

use Kobens\Exchange\AbstractExchange;
use Kobens\Exchange\Book\Keeper\KeeperInterface;
use Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper;
use Kobens\Gemini\Api\Param\{Amount, ClientOrderId, Price, Side, Symbol};
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder;
use Kobens\Gemini\Api\Rest\Request\Order\Status\{ActiveOrders, OrderStatus};
use Kobens\Gemini\Exception\Exception;
use Kobens\Gemini\Exchange\Pair\{BTCUSD, ETHBTC, ETHUSD, LTCBTC, LTCETH, LTCUSD, ZECBTC, ZECETH, ZECLTC, ZECUSD};

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

    public function isOrderCancelled(array $metaData): bool
    {
        if (!isset($metaData['is_cancelled'])) {
            throw new Exception('Metadata array missing "is_cancelled" information');
        }
        return $metaData['is_cancelled'] === true;
    }

    public function getActiveOrderIds(): array
    {
        $orders = \json_decode((new ActiveOrders())->getResponse()['body'], true);
        $orderIds = [];
        foreach ($orders as $order) {
            $orderIds[] = $order['order_id'];
        }
        return $orderIds;
    }

    public function getOrderMetaData(string $orderId): array
    {
        $json = (new OrderStatus($orderId))->getResponse();
        return \json_decode($json['body'], true);
    }

    public function isOrderFilled(array $metaData): bool
    {
        if (!isset($metaData['is_live']) || !isset($metaData['remaining_amount'])) {
            throw new Exception('Metadata missing required keys.');
        }
        return $metaData['is_live'] === false && $metaData['remaining_amount'] === '0';
    }

}

