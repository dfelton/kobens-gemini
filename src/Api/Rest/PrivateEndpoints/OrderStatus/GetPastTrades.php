<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetPastTradesInterface as I;

final class GetPastTrades extends AbstractPrivateRequest implements GetPastTradesInterface
{
    private const URL_PATH = '/v1/mytrades';

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var int
     */
    private $timestampms;

    /**
     * @var int
     */
    private $limitTrades;

    public function getTrades(string $symbol, int $timestampms = null, int $limitTrades = null): array
    {
        $this->symbol = $symbol;
        $this->timestampms = $timestampms;
        $this->limitTrades = $limitTrades >= I::LIMIT_MIN && $limitTrades <= I::LIMIT_MAX
            ? $limitTrades : null;
        return \json_decode($this->getResponse()['body']);
    }

    protected function getUrlPath(): string
    {
        return self::URL_PATH;
    }

    protected function getPayload(): array
    {
        $payload = ['symbol' => $this->symbol];
        if (!\is_null($this->timestampms)) {
            $payload['timestamp'] = $this->timestampms;
        }
        if (!\is_null($this->limitTrades)) {
            $payload['limit_trades'] = $this->limitTrades;
        }
        return $payload;
    }

}
