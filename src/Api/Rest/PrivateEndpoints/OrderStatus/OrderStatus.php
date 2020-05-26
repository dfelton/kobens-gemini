<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;

class OrderStatus implements OrderStatusInterface
{
    private const URL_PATH = '/v1/order/status';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function getStatus(int $orderId): \stdClass
    {
        $response = $this->request->getResponse(self::URL_PATH, ['order_id' => $orderId], [], true);
        return \json_decode($response->getBody());
    }

    public function getStatusByClientOrderId(string $clientOrderId): array
    {
        $response = $this->request->getResponse(self::URL_PATH, ['client_order_id' => $clientOrderId], [], true);
        return \json_decode($response->getBody());
    }
}
