<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

use Kobens\Core\Exception\ConnectionException;
use Kobens\Core\Exception\Http\CurlException;
use Kobens\Core\Exception\Http\RequestTimeoutException;
use Kobens\Core\Http\CurlInterface;
use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Core\Http\ResponseInterface;
use Kobens\Gemini\Exception;
use Kobens\Gemini\Exception\InvalidResponseException;
use Kobens\Gemini\Exception\ResourceMovedException;
use Psr\Log\LoggerInterface;

/**
 * TODO: Between the MAX_ITERATIONS and the timeouts this could run quite a while unchecked. Should maybe implement
 * some sort of injectable early exit check. Or do we let it throw here and choose higher up if retry is desired?
 *
 * Class Request
 * @package Kobens\Gemini\Api\PublicEndpoints
 */
final class Request implements RequestInterface
{
    private const MAX_ITERATIONS = 100;

    private HostInterface $host;

    private ThrottlerInterface $throttler;

    private CurlInterface $curl;

    private LoggerInterface $logger;

    public function __construct(
        HostInterface $hostInterface,
        ThrottlerInterface $throttlerInterface,
        CurlInterface $curlInterface,
        LoggerInterface $logger
    ) {
        $this->curl = $curlInterface;
        $this->host = $hostInterface;
        $this->throttler = $throttlerInterface;
        $this->logger = $logger;
    }

    /**
     * @param string $urlPath
     * @return ResponseInterface
     * @throws ConnectionException
     * @throws Exception
     * @throws InvalidResponseException
     * @throws RequestTimeoutException
     * @throws ResourceMovedException
     */
    public function makeRequest(string $urlPath): ResponseInterface
    {
        $i = 0;
        $response = null;
        do {
            try {
                $this->throttler->throttle();
                $response = $this->curl->request(
                    'https://' . $this->host->getHost() . $urlPath,
                    $this->getConfig()
                );
            } catch (CurlException $e) {
                $this->logger->warning(implode(
                    PHP_EOL,
                    [
                        $e->getMessage(),
                        $e->getTraceAsString()
                    ]
                ));
            }
        } while (!$response && ++$i < self::MAX_ITERATIONS);
        if (!$response) {
            throw new Exception('Max Iterations Reached');
        }
        $this->handle($response);
        return $response;
    }

    private function handle(ResponseInterface $response): void
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
            case $response->getResponseCode() >= 500:
                throw new InvalidResponseException(
                    $response->getBody(),
                    $response->getResponseCode(),
                    new \Exception(\json_encode($response))
                );
            default:
                break;
        }
    }

    private function getConfig(): array
    {
        return [
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_POST => false,
            CURLOPT_HTTPHEADER => [
                'cache-control:no-cache',
                'content-length:0',
                'content-type:text/plain',
            ]
        ];
    }

}
