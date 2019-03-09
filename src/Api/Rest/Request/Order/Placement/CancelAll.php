<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Placement;

use Kobens\Gemini\Api\Rest\Request;
use Kobens\Gemini\Exception\InvalidPayloadException;

class CancelAll extends Request
{
    const REQUEST_URI = '/v1/order/cancel/all';

    /**
     * @throws InvalidPayloadException
     * {@inheritDoc}
     * @see \Kobens\Gemini\Api\Rest\Request::setPayload()
     */
    public function setPayload(array $payload) : Request
    {
        if ($payload !== []) {
            throw new InvalidPayloadException(
                sprintf('Endpoint "%s" does not accept any unique payload parameters.', static::REQUEST_URI)
            );
        }
        return $this;
    }

}