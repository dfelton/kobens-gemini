<?php

namespace Kobens\Gemini\App\Actions\Order;

use Kobens\Core\ActionInterface;
use Kobens\Core\App\ResourcesInterface;
use Kobens\Core\Config\RuntimeInterface;
use Kobens\Core\Exception\RuntimeArgsInvalidException;
use Kobens\Gemini\Api\Rest\Request;

class NewOrder implements ActionInterface
{
    const API_ACTION_KEY = 'order:new';

    /**
     * @var ResourcesInterface
     */
    protected $app;

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

    protected $runtimeArgs = [];

    public function __construct(ResourcesInterface $resourcesInterface)
    {
        $this->app = $resourcesInterface;
    }

    public function setPayload(array $payload) : Request
    {
        $filtered = [];
        foreach ($this->runtimeArgOptions as $key => $config) {
            if (\array_key_exists($key, $payload)) {
                $filtered[$config['payload_key']] = $payload[$key];
            } elseif ($config['required'] === true) {
                throw new RuntimeArgsInvalidException(\sprintf(
                    '"%s" is missing required runtime parameter "%s"',
                    self::class,
                    $key
                ));
            }
        }
        return parent::setPayload(\array_merge($this->defaultPayload, $filtered));
    }

    public function execute() : void
    {
        throw new \Exception('not implemented');
    }

    public function setRuntimeArgs(array $args): RuntimeInterface
    {
        $this->runtimeArgs = $args;
        return $this;
    }

    public function getRuntimeArgOptions(): array
    {
        return $this->runtimeArgOptions;
    }

}