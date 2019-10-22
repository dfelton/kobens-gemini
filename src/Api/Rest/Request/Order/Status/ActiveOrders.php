<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Status;

use Kobens\Gemini\Api\Rest\Request;

class ActiveOrders extends Request implements ActiveOrdersInterface
{
    const REQUEST_URI = '/v1/orders';
}