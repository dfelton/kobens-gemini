<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model;

final class Trade
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $isEnabled;

    /**
     * @var int
     */
    private $isError;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string
     */
    private $buyAmount;

    /**
     * @var string
     */
    private $buyPrice;

    /**
     * @var string
     */
    private $sellAmount;

    /**
     * @var string
     */
    private $sellPrice;

    /**
     * @var string
     */
    private $buyClientOrderId;

    /**
     * @var string
     */
    private $buyOrderId;

    /**
     * @var string
     */
    private $sellClientOrderId;

    /**
     * @var string
     */
    private $sellOrderId;

    /**
     * @var string
     */
    private $note;

    /**
     * @var string
     */
    private $meta;

    /**
     * @var string
     */
    private $updatedAt;

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
        string $buyOrderId = null,
        string $sellClientOrderId = null,
        string $sellOrderId = null,
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

    public function getIsEnabled(): int
    {
        return $this->isEnabled;
    }

    public function getIsError(): int
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

    public function getAmount(): string
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

    public function getBuyOrderId(): ?string
    {
        return $this->buyOrderId;
    }

    public function getSellClientOrderId(): ?string
    {
        return $this->sellClientOrderId;
    }

    public function getSellOrderId(): ?string
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
