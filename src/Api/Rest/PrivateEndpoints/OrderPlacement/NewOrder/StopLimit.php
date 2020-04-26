<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;

final class StopLimit extends AbstractPrivateRequest implements StopLimitInterface
{
    private const URL_PATH = '/v1/order/new';

    /**
     * @var array
     */
    protected $payload = [];

    public function place(PairInterface $pair, string $side, string $amount, string $price, string $stopPrice, string $clientOrderId = null): \stdClass
    {
        $this->payload = [
            'type' => 'exchange stop limit',
            'symbol' => $pair->getSymbol(),
            'amount' => $amount,
            'price'  => $price,
            'stop_price' => $stopPrice,
            'side'   => $side,
        ];
        if ($clientOrderId) {
            $this->payload['client_order_id'] = $clientOrderId;
        };
        return \json_decode($this->getResponse()->getBody());
    }

    final protected function getUrlPath(): string
    {
        return self::URL_PATH;
    }

    /**
     * It is up to the concrete class to set this prior to calling getResponse()
     *
     * {@inheritDoc}
     * @see \Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest::getPayload()
     */
    final protected function getPayload(): array
    {
        return $this->payload;
    }

}
