<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;
use Kobens\Gemini\Exception\Api\InsufficientFundsException;

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

    final protected function throwResponseException(array $response): void
    {
        parent::throwResponseException($response);
        $obj = \json_decode($response['body']);
        // @todo narrow this down to a single ==== comparison
        if ($response['code'] >= 400 && $response['code'] < 500) {
            if ($obj->reason === InsufficientFundsException::REASON) {
                throw new InsufficientFundsException(
                    $obj->message,
                    $response['code'],
                    new \Exception(\json_encode($response))
                );
            }
        }
    }
}
