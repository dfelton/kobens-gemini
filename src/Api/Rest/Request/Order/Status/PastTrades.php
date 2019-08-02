<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Status;

use Kobens\Gemini\Api\Rest\Request;

final class PastTrades extends Request
{
    const REQUEST_URI = '/v1/mytrades';

    const LIMIT_MAX = 500;
    const LIMIT_MIN = 1;

    public function __construct(string $symbol, int $timestamp = -1, int $limit = 500)
    {
        parent::__construct();
        $this->payload['symbol'] = $symbol;
        $this->setLimit($limit);
        $this->setTimestamp($timestamp);
    }

    public function setLimit(int $limit): self
    {
        if ($limit > self::LIMIT_MAX || $limit < self::LIMIT_MIN) {
            throw new \Exception (\sprintf("Limit must be within a range of %d-%d", self::LIMIT_MIN, self::LIMIT_MAX));
        }
        $this->payload['limit_trades'] = $limit;
        return $this;
    }

    public function setTimestamp(int $timestamp): self
    {
        if ($timestamp === -1) {
            unset($this->payload['timestamp']);
        } else {
            $this->payload['timestamp'] = $timestamp;
        }
        return $this;
    }
}