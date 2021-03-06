<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\CliMonitor\Helper;

use Kobens\Gemini\Api\Market\GetPrice\Result;

interface DataInterface
{
    public function reset(): void;

    public function getPriceResult(string $symbol): Result;

    /**
     * @return \stdClass[]
     */
    public function getOrdersData(): array;

    /**
     * @return \Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface[]
     */
    public function getNotionalBalances(): array;

    public function getExtra(): array;

    public function getProfitsBucketValue(string $bucket): string;

    public function getNotional(string $symbol, string $amount): string;
}
