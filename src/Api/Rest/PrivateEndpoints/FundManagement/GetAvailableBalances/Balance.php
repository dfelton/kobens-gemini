<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances;

final class Balance implements BalanceInterface
{
    private string $currency;

    private string $amount;

    private string $available;

    private string $availableForWithdrawal;

    public function __construct(
        string $currency,
        string $amount,
        string $available,
        string $availableForWithdrawal
    ) {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->available = $available;
        $this->availableForWithdrawal = $availableForWithdrawal;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getAvailable(): string
    {
        return $this->available;
    }

    public function getAvailableForWithdrawal(): string
    {
        return $this->availableForWithdrawal;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
