<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;
use Kobens\Http\Exception\Status\ServerError\BadGatewayException;
use Psr\Log\LoggerInterface;
use Kobens\Core\Http\ResponseInterface;

class OrderStatus implements OrderStatusInterface
{
    private const MSG_MAX_ATTEMPTS_EXCEEDED = '"%s" exceeded maximum attempts of "%d"';
    private const URL_PATH = '/v1/order/status';

    private LoggerInterface $logger;

    private RequestInterface $request;

    private int $maxAttempts;

    public function __construct(
        LoggerInterface $logger,
        RequestInterface $requestInterface,
        int $maxAttempts = 10
    ) {
        $this->logger = $logger;
        $this->request = $requestInterface;
        $this->maxAttempts = $maxAttempts;
    }

    public function getStatus(int $orderId): \stdClass
    {
        return \json_decode($this->getResponse(['order_id' => $orderId])->getBody());
    }

    public function getStatusByClientOrderId(string $clientOrderId): array
    {
        return \json_decode($this->getResponse(['client_order_id' => $clientOrderId])->getBody());
    }

    private function getResponse(array $payload): ResponseInterface
    {
        $attempts = 0;
        while (true) {
            try {
                return $this->request->getResponse(self::URL_PATH, $payload, [], true);
            } catch (BadGatewayException $e) {
                $this->logger->error($e->__toString());
                if (++$attempts > $this->maxAttempts) {
                    throw new \Exception(
                        sprintf(
                            self::MSG_MAX_ATTEMPTS_EXCEEDED,
                            __METHOD__,
                            $this->maxAttempts
                        ),
                        0,
                        $e
                    );
                }
                usleep(500);
            }
        }
    }
}
