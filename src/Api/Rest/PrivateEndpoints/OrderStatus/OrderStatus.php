<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;

class OrderStatus extends AbstractPrivateRequest implements OrderStatusInterface
{
    const URL_PATH = '/v1/order/status';

    /**
     * @var int
     */
    private $orderId;

    public function getStatus(int $orderId): \stdClass
    {
        $this->orderId = $orderId;
        return \json_decode($this->getResponse()->getBody());
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
