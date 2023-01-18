<?php

declare(strict_types=1);

namespace Kobens\Gemini\ApiModels;

interface TradeInterface
{
    public function getTradeId(): int;

    public function getPrice(): string;

    public function getAmount(): string;

    public function getTimestampMs(): int;

    public function getType(): string;

    public function getAggressor(): bool;

    public function getFeeCurrency(): string;

    public function getFeeAmount(): string;

    public function getOrderId(): int;

    public function getClientOrderId(): string;

    public function getTradeDate(): string;
}
