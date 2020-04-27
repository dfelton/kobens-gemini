<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface;

interface GetNotionalBalancesInterface
{
    /**
     * @return BalanceInterface[]
     */
    public function getBalances(): array;

    public function getBalance(string $currency): BalanceInterface;
}