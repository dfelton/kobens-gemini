<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;
use Kobens\Gemini\Exception\Api\Reason\InvalidNonceException;
use Kobens\Http\Exception\Status\ServerError\BadGatewayException;
use Kobens\Http\Exception\Status\ServerError\GatewayTimeoutException;

final class CancelOrder implements CancelOrderInterface
{
    private const URL_PATH = '/v1/order/cancel';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    /**
     * TODO: remove InvalidNonceException once better keys management is in place
     *
     * {@inheritDoc}
     * @see \Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelOrderInterface::cancel()
     */
    public function cancel(int $orderId): \stdClass
    {
        $response = null;
        $i = 0;
        while ($response === null && ++$i <= 15) {
            try {
                $response = $this->request->getResponse(self::URL_PATH, ['order_id' => $orderId]);
            } catch (BadGatewayException | GatewayTimeoutException | InvalidNonceException $e) {
                if ($i >= 15) {
                    throw $e;
                }
            }
        }
        return \json_decode($response->getBody());
    }
}
