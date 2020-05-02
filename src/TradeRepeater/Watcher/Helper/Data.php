<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Watcher\Helper;

use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Api\Market\GetPrice\Result;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalancesInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrdersInterface;

final class Data implements DataInterface
{
    /**
     * @var Result[]
     */
    private array $priceResult = [];

    /**
     * @var \stdClass[]
     */
    private ?array $orders = null;

    private GetActiveOrdersInterface $activeOrders;

    private GetPriceInterface $price;

    private GetNotionalBalancesInterface $notionalBalance;

    /**
     * @var \Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface[]
     */
    private ?array $notionalBalances = null;

    public function __construct(
        GetActiveOrdersInterface $getActiveOrdersInterface,
        GetPriceInterface $getPriceInterface,
        GetNotionalBalancesInterface $getNotionalBalancesInterface
    ) {
        $this->activeOrders = $getActiveOrdersInterface;
        $this->price = $getPriceInterface;
        $this->notionalBalance = $getNotionalBalancesInterface;
    }

    public function reset(): void
    {
        $this->priceResult = [];
        $this->orders = null;
        $this->notionalBalances = null;
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

    /**
     * @return \Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface[]
     */
    public function getNotionalBalances(): array
    {
        if ($this->notionalBalances === null) {
            $this->notionalBalances = $this->notionalBalance->getBalances();
        }
        return $this->notionalBalances;
    }
}
