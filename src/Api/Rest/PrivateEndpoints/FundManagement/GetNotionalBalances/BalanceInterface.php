<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances;

interface BalanceInterface
{
    public function getCurrency(): string;

    public function getAmount(): string;

    public function getAmountNotional(): string;

    public function getAvailable(): string;

    public function getAvailableNotional(): string;

    public function getAvailableForWithdrawal(): string;

    public function getAvailableForWithdrawalNotional(): string;
}
