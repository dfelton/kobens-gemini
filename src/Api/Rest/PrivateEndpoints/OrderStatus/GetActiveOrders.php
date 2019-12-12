<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;

class GetActiveOrders extends AbstractPrivateRequest implements GetActiveOrdersInterface
{
    const URL_PATH = '/v1/orders';

    public function getOrders(): array
    {
        return \json_decode($this->getResponse()['body']);
    }

    protected function getUrlPath(): string
    {
        return self::URL_PATH;
    }

    protected function getPayload(): array
    {
        return [];
    }

}