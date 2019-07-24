<?php

namespace Kobens\Gemini;

use Kobens\Exchange\AbstractExchange;
use Kobens\Exchange\Book\Keeper\KeeperInterface;
use Kobens\Exchange\Order\StatusInterface;
use Kobens\Gemini\Api\WebSocket\MarketData\BookKeeper;
use Kobens\Gemini\Api\Param\{Amount, ClientOrderId, Price, Side, Symbol};
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder;
use Kobens\Gemini\Api\Rest\Request\Order\Status\{ActiveOrders, OrderStatus};
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
        // @todo error handling
        $order = new NewOrder(
            new Side($side),
            new Symbol($this->getPair($symbol)),
            new Amount($amount),
            new Price($price),
            new ClientOrderId()
        );
        return \json_decode($order->getResponse()['body'], false)->order_id;
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

    public function getStatusInterface(): StatusInterface
    {
        return new Status();
    }
}
