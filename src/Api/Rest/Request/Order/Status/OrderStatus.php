<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Status;

use Kobens\Gemini\Api\Rest\Request;

class OrderStatus extends Request
{
    const REQUEST_URI = '/v1/order/status';

    public function __construct(int $orderId)
    {
        parent::__construct();
        $this->payload['order_id'] = $orderId;
    }
}