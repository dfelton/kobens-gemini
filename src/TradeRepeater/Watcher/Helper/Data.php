<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Watcher\Helper;

use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Api\Market\GetPrice\Result;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrdersInterface;

final class Data implements DataInterface
{
    /**
     * @var Result[]
     */
    private $priceResult = [];

    /**
     * @var \stdClass[]
     */
    private $orders;

    /**
     * @var GetActiveOrdersInterface
     */
    private $activeOrders;

    /**
     * @var GetPriceInterface
     */
    private $price;

    public function __construct(
        GetActiveOrdersInterface $getActiveOrdersInterface,
        GetPriceInterface $getPriceInterface
    ) {
        $this->activeOrders = $getActiveOrdersInterface;
        $this->price = $getPriceInterface;
    }

    public function reset(): void
    {
        $this->priceResult = [];
        $this->orders = null;
    }

    public function getPriceResult(string $symbol): Result
    {
        if (!($this->priceResult[$symbol] ?? null)) {
            $this->priceResult[$symbol] = $this->price->getResult($symbol);
        }
        return $this->priceResult[$symbol];
    }

    /**
     * @return \stdClass[]
     */
    public function getOrdersData(): array
    {
        if ($this->orders === null) {
            $this->orders = $this->activeOrders->getOrders();
        }
        return $this->orders;
    }
}
