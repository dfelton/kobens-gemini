<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Helper;

use Kobens\Core\Exception\ConnectionException;
use Kobens\Core\Exception\Http\RequestTimeoutException;
use Kobens\Core\Http\ResponseInterface;
use Kobens\Gemini\Exception\InvalidResponseException;
use Kobens\Gemini\Exception\ResourceMovedException;
use Kobens\Http\Exception\Status\ServerErrorException;
use Kobens\Http\Exception\Status\ServerError\BadGatewayException;
use Kobens\Http\Exception\Status\ServerError\GatewayTimeoutException;
use Kobens\Http\Exception\Status\ServerError\ServiceUnavailableException;

final class ResponseHandler
{
    private $responseCodeMap = [
        500 => ServerErrorException::class,
        502 => BadGatewayException::class,
        503 => ServiceUnavailableException::class,
        504 => GatewayTimeoutException::class,
    ];

    public function handleResponse(ResponseInterface $response): void
    {
        $body = @\json_decode($response->getBody()); // 504 responses come back as HTML
        switch (true) {
            case $body instanceof \stdClass && ($body->result ?? null) === 'error' && $body->reason ?? null:
                $className = "\Kobens\Gemini\Exception\Api\Reason\{$body->reason}Exception";
                if (!\class_exists($className)) {
                    $className = \Kobens\Gemini\Exception::class;
                }
                throw new $className(
                    $body->message,
                    $response->getResponseCode(),
                    new \Exception(\json_encode($response))
                );
            case $response->getResponseCode() === 0:
                // TODO Do we ever enter here? our updates to always check curl error I think not...
                throw new ConnectionException(\sprintf(
                    'Unable to establish a connection with "%s"',
                    $this->host->getHost(),
                    new \Exception(\json_encode($response))
                ));
            case $response->getResponseCode() >= 300 && $response->getResponseCode() < 400:
                throw new ResourceMovedException(
                    'Resource Has Moved',
                    $response->getResponseCode(),
                    new \Exception(\json_encode($response))
                );
            case $response->getResponseCode() === 408:
                throw new RequestTimeoutException(
                    \sprintf('%s timed out interacting with server.', static::class),
                    new \Exception(\json_encode($response))
                );
            case $response->getResponseCode() === 500:
            case $response->getResponseCode() === 502:
            case $response->getResponseCode() === 503:
            case $response->getResponseCode() === 504:
                throw new $this->responseCodeMap[$response->getResponseCode()](
                    null,
                    $response->getResponseCode(),
                    new \Exception(\json_encode($response))
                );

            case $response->getResponseCode() >= 500:
                throw new ServerErrorException(
                    null,
                    $response->getResponseCode(),
                    new \Exception(\json_encode($response))
                );
        }
    }
}
