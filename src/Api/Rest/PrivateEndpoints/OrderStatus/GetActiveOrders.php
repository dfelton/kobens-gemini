<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;

class GetActiveOrders implements GetActiveOrdersInterface
{
    protected const URL_PATH = '/v1/orders';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function getOrders(): array
    {
        $response = $this->request->getResponse(self::URL_PATH, [], [], true);
        return \json_decode($response->getBody());
    }
}
