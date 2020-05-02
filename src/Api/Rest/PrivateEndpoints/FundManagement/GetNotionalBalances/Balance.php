<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances;

final class Balance implements BalanceInterface
{
    private string $currency;

    private string $amount;

    private string $amountNotional;

    private string $available;

    private string $availableNotional;

    private string $availableForWithdrawal;

    private string $availableForWithdrawalNotional;

    public function __construct(
        string $currency,
        string $amount,
        string $amountNotional,
        string $available,
        string $availableNotional,
        string $availableForWithdrawal,
        string $availableForWithdrawalNotional
    ) {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->amountNotional = $amountNotional;
        $this->available = $available;
        $this->availableNotional = $availableNotional;
        $this->availableForWithdrawal = $availableForWithdrawal;
        $this->availableForWithdrawalNotional = $availableForWithdrawalNotional;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getAmountNotional(): string
    {
        return $this->amountNotional;
    }

    public function getAvailable(): string
    {
        return $this->available;
    }

    public function getAvailableNotional(): string
    {
        return $this->availableNotional;
    }

    public function getAvailableForWithdrawal(): string
    {
        return $this->availableForWithdrawal;
    }

    public function getAvailableForWithdrawalNotional(): string
    {
        return $this->availableForWithdrawalNotional;
    }
}
