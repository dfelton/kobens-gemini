<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;

final class StopLimit implements StopLimitInterface
{
    private const URL_PATH = '/v1/order/new';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function place(PairInterface $pair, string $side, string $amount, string $price, string $stopPrice, string $clientOrderId = null): \stdClass
    {
        $payload = [
            'type' => 'exchange stop limit',
            'symbol' => $pair->getSymbol(),
            'amount' => $amount,
            'price'  => $price,
            'stop_price' => $stopPrice,
            'side'   => $side,
        ];
        if ($clientOrderId) {
            $payload['client_order_id'] = $clientOrderId;
        };
        $response = $this->request->getResponse(self::URL_PATH, $payload);
        return \json_decode($response->getBody());
    }
}
