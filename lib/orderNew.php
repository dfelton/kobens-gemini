<?php

require_once __DIR__.'/traderRequest.php';
require_once __DIR__.'/restKeyInterface.php';

class OrderNew extends TraderRequest
{
    const REQUEST_URI = '/v1/order/new';

    /**
     *
     * @param RestKeyInterface $restApiInterface
     * @param string $symbol
     * @param string $price
     * @param string $amount
     * @param string $side
     */
    public function __construct(RestKeyInterface $restApiInterface, $symbol, $price, $amount, $side)
    {
        parent::__construct(
            $restApiInterface,
            [
                'client_order_id' => 'test:btcusd:'.time().$amount.':'.$price,
                'symbol' => $symbol,
                'amount' => $amount,
                'price' => $price,
                'side' => $side,
                'type' => 'exchange limit',
                'options' => [
                    0 => 'maker-or-cancel'
                ]
            ]
        );
    }

    protected function validatePayload()
    {
        // TODO
        return true;
    }

}