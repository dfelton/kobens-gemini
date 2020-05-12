<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;

final class CancelOrder implements CancelOrderInterface
{
    private const URL_PATH = '/v1/order/cancel';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function cancel(int $orderId): \stdClass
    {
        $response = $this->request->getResponse(self::URL_PATH, ['order_id' => $orderId]);
        return \json_decode($response->getBody());
    }
}
