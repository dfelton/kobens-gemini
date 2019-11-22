<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;

abstract class AbstractNewOrder extends AbstractPrivateRequest implements NewOrderInterface
{
    private const URL_PATH = '/v1/order/new';

    /**
     * @var array
     */
    protected $payload = [];

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
