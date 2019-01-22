<?php

require_once 'traderRequest.php';
require_once 'restKeyInterface.php';

class CancelAllOrders extends TraderRequest
{
    const REQUEST_URI = '/v1/order/cancel/all';

    protected function validatePayload()
    {
        if ($this->payload !== []) {
            throw new Exception('Invalid Cancal All Orders Payload');
        }
    }

}