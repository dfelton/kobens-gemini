<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;

final class CancelOrder extends AbstractPrivateRequest implements CancelOrderInterface
{
    private const URL_PATH = '/v1/order/cancel';

    /**
     * @var int
     */
    private $orderId;

    public function cancel(int $orderId): \stdClass
    {
        $this->orderId = $orderId;
        return \json_decode($this->getResponse()['body']);
    }

    protected function getUrlPath(): string
    {
        return self::URL_PATH;
    }

    protected function getPayload(): array
    {
        return ['order_id' => $this->orderId];
    }
}
