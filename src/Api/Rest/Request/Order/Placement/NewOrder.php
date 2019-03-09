<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Placement;

use Kobens\Gemini\Api\Rest\Request;

class NewOrder extends Request
{
    const REQUEST_URI = '/v1/order/new';

    /**
     * @todo refactor out of the class more array data
     *
     * @var array
     */
    protected $runtimeArgOptions = [
        'order_client_order_id' => [
            'required' => false,
            'help' => 'Client Order Id (Optional)',
            'payload_key' => 'client_order_id',
        ],
        'order_symbol' => [
            'required' => true,
            'help' => 'Currency Pair Symbol',
            'payload_key' => 'symbol',
        ],
        'order_amount' => [
            'required' => true,
            'help' => 'Base Currency Order Amount',
            'payload_key' => 'amount',
        ],
        'order_price' => [
            'required' => true,
            'help' => 'Quote Currency Price',
            'payload_key' => 'price',
        ],
        'order_side' => [
            'required' => true,
            'help' => 'Order Book Side',
            'payload_key' => 'side'
        ],
    ];

    protected $defaultPayload = [
        'type' => 'exchange limit',
        'options' => ['maker-or-cancel'],
    ];

    public function setPayload(array $payload) : Request
    {
        $filtered = [];
        foreach ($this->runtimeArgOptions as $key => $config) {
            if (\array_key_exists($key, $payload)) {
                $filtered[$config['payload_key']] = $payload[$key];
            } elseif ($config['required'] === true) {
                // @todo more specific exception class
                throw new \Exception(\sprintf(
                    '"%s" is missing required runtime parameter "%s"',
                    self::class,
                    $key
                ));
            }
        }
        return parent::setPayload(\array_merge($this->defaultPayload, $filtered));
    }

}