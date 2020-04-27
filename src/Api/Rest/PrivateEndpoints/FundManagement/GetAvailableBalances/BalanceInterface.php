<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances;

interface BalanceInterface
{
    public function getAmount(): string;

    public function getAvailable(): string;

    public function getAvailableForWithdrawal(): string;

    public function getCurrency(): string;
}
