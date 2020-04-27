<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances;

final class Balance implements BalanceInterface
{
    /**
     * @var string
     */
    private $currency;

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
