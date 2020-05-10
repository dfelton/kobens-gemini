<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetPastTradesInterface as I;

final class GetPastTrades extends AbstractPrivateRequest implements GetPastTradesInterface
{
    private const URL_PATH = '/v1/mytrades';

    private string $symbol;

    private ?int $timestampms;

    private ?int $limitTrades;

    public function getTrades(string $symbol, int $timestampms = null, int $limitTrades = null): array
    {
        $this->symbol = $symbol;
        $this->timestampms = $timestampms;
        $this->limitTrades = $limitTrades >= I::LIMIT_MIN && $limitTrades <= I::LIMIT_MAX
            ? $limitTrades : null;
        return \json_decode($this->getResponse()->getBody());
    }

    protected function getUrlPath(): string
    {
        return self::URL_PATH;
    }

    protected function getPayload(): array
    {
        $payload = ['symbol' => $this->symbol];
        if ($this->timestampms !== null) {
            $payload['timestamp'] = $this->timestampms;
        }
        if ($this->limitTrades !== null) {
            $payload['limit_trades'] = $this->limitTrades;
        }
        return $payload;
    }

}
