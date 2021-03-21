<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;
use Kobens\Http\Exception\Status\ServerError\BadGatewayException;
use Kobens\Http\Exception\Status\ServerError\GatewayTimeoutException;

final class GetActiveOrders implements GetActiveOrdersInterface
{
    private const URL_PATH = '/v1/orders';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function getOrders(): array
    {
        $response = null;
        $i = 0;
        while ($response === null && ++$i <= 15) {
            try {
                $response = $this->request->getResponse(self::URL_PATH, [], [], true);
            } catch (BadGatewayException | GatewayTimeoutException $e) {
                if ($i >= 15) {
                    throw $e;
                }
            }
        }
        return \json_decode($response->getBody());
    }
}
