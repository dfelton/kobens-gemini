<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;

final class CancelAllActiveOrders implements CancelAllActiveOrdersInterface
{
    private const URL_PATH = '/v1/order/cancel/all';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function cancelAll(): \stdClass
    {
        $response = $this->request->getResponse(self::URL_PATH);
        return \json_decode($response->getBody());
    }
}
