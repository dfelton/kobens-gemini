<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model;

final class Trade
{
    private ?int $id;

    private int $isEnabled;

    private int $isError;

    private string $status;

    private string $symbol;

    private string $buyAmount;

    private string $buyPrice;

    private string $sellAmount;

    private string $sellPrice;

    private ?string $buyClientOrderId;

    private ?int $buyOrderId;

    private ?string $sellClientOrderId;

    private ?int $sellOrderId;

    private ?string $note;

    private ?string $meta;

    private ?string $updatedAt;

    public function __construct(
        int $id = null,
        int $isEnabled,
        int $isError,
        string $status,
        string $symbol,
        string $buyAmount,
        string $buyPrice,
        string $sellAmount,
        string $sellPrice,
        string $buyClientOrderId = null,
        int $buyOrderId = null,
        string $sellClientOrderId = null,
        int $sellOrderId = null,
        string $note = null,
        string $meta = null,
        string $updatedAt = null
    ) {
        $this->id = $id;
        $this->isEnabled = $isEnabled;
        $this->isError = $isError;
        $this->status = $status;
        $this->symbol = $symbol;
        $this->buyAmount = $buyAmount;
        $this->buyPrice = $buyPrice;
        $this->buyClientOrderId = $buyClientOrderId;
        $this->buyOrderId = $buyOrderId;
        $this->sellAmount = $sellAmount;
        $this->sellPrice = $sellPrice;
        $this->sellClientOrderId = $sellClientOrderId;
        $this->sellOrderId = $sellOrderId;
        $this->note = $note;
        $this->meta = $meta;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEnabled(): int
    {
        return $this->isEnabled;
    }

    public function isError(): int
    {
        return $this->isError;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getBuyAmount(): string
    {
        return $this->buyAmount;
    }

    public function getBuyPrice(): string
    {
        return $this->buyPrice;
    }

    public function getSellAmount(): string
    {
        return $this->sellAmount;
    }

    public function getSellPrice(): string
    {
        return $this->sellPrice;
    }

    public function getBuyClientOrderId(): ?string
    {
        return $this->buyClientOrderId;
    }

    public function getBuyOrderId(): ?int
    {
        return $this->buyOrderId;
    }

    public function getSellClientOrderId(): ?string
    {
        return $this->sellClientOrderId;
    }

    public function getSellOrderId(): ?int
    {
        return $this->sellOrderId;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
}
