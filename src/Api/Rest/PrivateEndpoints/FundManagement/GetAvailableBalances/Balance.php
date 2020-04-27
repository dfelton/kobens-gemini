<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances;

final class Balance implements BalanceInterface
{
    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $available;

    /**
     * @var string
     */
    private $availableForWithdrawal;

    /**
     * @var string
     */
    private $currency;

    public function __construct(string $amount, string $available, string $availableForWithdrawal, string $currency)
    {
        $this->amount = $amount;
        $this->available = $available;
        $this->availableForWithdrawal = $availableForWithdrawal;
        $this->currency = $currency;
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
