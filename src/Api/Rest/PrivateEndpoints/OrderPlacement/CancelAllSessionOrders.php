<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;

final class CancelAllSessionOrders implements CancelAllSessionOrdersInterface
{
    private const URL_PATH = '/v1/order/cancel/session';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function cancelSessionOrders(): \stdClass
    {
        $response = $this->request->getResponse(self::URL_PATH);
        return \json_decode($response->getBody());
    }
}
