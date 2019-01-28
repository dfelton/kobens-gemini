<?php

require_once __DIR__.'/traderRequest.php';
require_once __DIR__.'/restKeyInterface.php';

class CancelAllOrders extends TraderRequest
{
    const REQUEST_URI = '/v1/order/cancel/all';

    protected function validatePayload()
    {
        if ($this->payload !== []) {
            throw new Exception('Invalid cancel all orders payload');
        }
    }

}