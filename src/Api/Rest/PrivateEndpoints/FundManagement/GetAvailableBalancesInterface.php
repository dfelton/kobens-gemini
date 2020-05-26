<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances\BalanceInterface;

interface GetAvailableBalancesInterface
{
    /**
     * @return BalanceInterface[]
     */
    public function getBalances(): array;

    public function getBalance(string $currency): BalanceInterface;
}
