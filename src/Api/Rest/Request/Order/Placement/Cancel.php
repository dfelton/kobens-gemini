<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Placement;

use Kobens\Gemini\Api\Rest\Request;

class Cancel extends Request
{
    const REQUEST_URI = '/v1/order/cancel';

    public function __construct(string $orderId)
    {
        parent::__construct();
        $this->payload = ['order_id' => $orderId];
    }
}