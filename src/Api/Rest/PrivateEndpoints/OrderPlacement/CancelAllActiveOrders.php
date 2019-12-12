<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;

final class CancelAllActiveOrders extends AbstractPrivateRequest implements CancelAllActiveOrdersInterface
{
    private const URL_PATH = '/v1/order/cancel/all';

    public function cancelAll(): \stdClass
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