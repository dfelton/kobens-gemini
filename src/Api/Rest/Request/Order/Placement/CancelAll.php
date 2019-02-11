<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Placement;

use \Kobens\Gemini\Api\Rest\Request;
use \Kobens\Gemini\Exception\InvalidPayloadException;
use \Kobens\Core\ActionInterface;

class CancelAll extends Request
    implements ActionInterface
{
    const API_ACTION_KEY = 'order:cancel-all';

    const REQUEST_URI = '/v1/order/cancel/all';

    public function __construct(
        \Kobens\Core\App\ResourcesInterface $appResources
    ) {
        if ($appResources->getConfig()->gemini->api->host === \Kobens\Gemini\Api\Host::PRODUCTION) {
            throw new \Exception('Cancel All Active Orders is not allowed in production.');
        }
        parent::__construct($appResources);
    }

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

    public function execute() : ActionInterface
    {
        $response = $this->makeRequest()->getResponse();

        if ($response->result === 'ok') {
            $this->appResources->getOutput()->write(sprintf('Total of %d active orders cancelled', count($response->details->cancelledOrders)));
            $this->appResources->getOutput()->write(sprintf('Total of %d active orders rejected for cancellation', count($response->details->cancelRejects)));
        } else {
            $this->appResources->getOutput()->write('There was a problem cancelling all active orders.');
        }

        return $this;
    }

}