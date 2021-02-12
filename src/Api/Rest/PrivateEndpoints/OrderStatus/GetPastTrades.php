<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetPastTradesInterface as I;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;
use Kobens\Core\Http\ResponseInterface;
use Kobens\Gemini\Exception\Api\Reason\InvalidNonceException;

final class GetPastTrades implements GetPastTradesInterface
{
    private const URL_PATH = '/v1/mytrades';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function getTrades(string $symbol, int $timestampms = null, int $limitTrades = null): array
    {
        $response = null;
        $payload = $this->getPayload($symbol, $timestampms, $limitTrades);
        $i = 0;
        while ($response === null && ++$i <= 15) {
            try {
                $response = $this->request->getResponse(self::URL_PATH, $payload, [], true);
            } catch (InvalidNonceException $e) {
                continue;
            }
        }
        if ($response === null) {
            throw new \Exception('Failed to fetch response for past trades.');
        }
        return \json_decode($response->getBody());
    }

    private function getPayload(string $symbol, int $timestampms, int $limitTrades): array
    {
        $payload = ['symbol' => $symbol];
        if ($timestampms !== null) {
            $payload['timestamp'] = $timestampms;
        }
        if ($limitTrades !== null) {
            if ($limitTrades < I::LIMIT_MIN || $limitTrades > I::LIMIT_MAX) {
                throw new \LogicException(sprintf(
                    'Limit "%d" is invalid. Must be between "%d" and "%d"',
                    $limitTrades,
                    I::LIMIT_MIN,
                    I::LIMIT_MAX
                ));
            }
            $payload['limit_trades'] = $limitTrades;
        }
        return $payload;
    }
}
