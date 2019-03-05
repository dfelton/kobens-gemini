<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Placement;

class NewOrder extends \Kobens\Gemini\Api\Rest\Request
    implements \Kobens\Core\ActionInterface
{
    const API_ACTION_KEY = 'order:new';

    const REQUEST_URI = '/v1/order/new';

    /**
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

    public function setPayload(array $payload) : \Kobens\Gemini\Api\Rest\Request
    {
        $filtered = [];
        foreach ($this->runtimeArgOptions as $key => $config) {
            if (\array_key_exists($key, $payload)) {
                $filtered[$config['payload_key']] = $payload[$key];
            } elseif ($config['required'] === true) {
                throw new \Kobens\Core\Exception\RuntimeArgsInvalidException(\sprintf(
                    '"%s" is missing required runtime parameter "%s"',
                    self::class,
                    $key
                ));
            }
        }
        return parent::setPayload(\array_merge($this->defaultPayload, $filtered));
    }

    /**
     * {@inheritDoc}
     * @see \Kobens\Core\ActionInterface::execute()
     */
    public function execute() : void
    {
        // TODO: abstract this into Kobens\Gemini\App\Actions\Order\NewOrder
        $this->makeRequest()->getResponse();
    }

}