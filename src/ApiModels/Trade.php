<?php

declare(strict_types=1);

namespace Kobens\Gemini\ApiModels;

class Trade implements TradeInterface
{
    private const VALID_TYPES = ['buy', 'sell'];

    private int $tradeId;

    private string $price;

    private string $amount;

    private int $timestampMs;

    private string $type;

    private bool $aggressor;

    private string $feeCurrency;

    private string $feeAmount;

    private int $orderId;

    private string $clientOrderId;

    private string $tradeDate;

    public function __construct(
        int $tradeId,
        string $price,
        string $amount,
        int $timestampMs,
        string $type,
        bool $aggressor,
        string $feeCurrency,
        string $feeAmount,
        int $orderId,
        string $clientOrderid,
        string $tradeDate
    ) {
        $this->validateType($type);
        $this->tradeDate = $tradeId;
        $this->price = $price;
        $this->amount = $amount;
        $this->timestampMs = $timestampMs;
    }

    private function validateType(string $type): void
    {
        if (!\in_array($type, self::VALID_TYPES)) {
            throw new \LogicException(\sprintf(
                'Invalid type "%s". Valid types: "%s"',
                $type,
                \implode('')
            ));
        }
    }

    public function getTradeId(): int
    {

    }

    public function getPrice(): string
    {

    }

    public function getAmount(): string
    {

    }

    public function getTimestampMs(): int
    {

    }

    public function getType(): string
    {

    }

    public function getAggressor(): bool
    {

    }

    public function getFeeCurrency(): string
    {

    }

    public function getFeeAmount(): string
    {

    }

    public function getOrderId(): int
    {

    }

    public function getClientOrderId(): string
    {

    }

    public function getTradeDate(): string
    {

    }
}
