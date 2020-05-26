<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

use Kobens\Core\Exception\ConnectionException;
use Kobens\Core\Exception\Http\CurlException;
use Kobens\Core\Exception\Http\RequestTimeoutException;
use Kobens\Core\Http\CurlInterface;
use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Gemini\Api\Helper\ResponseHandler;
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

    private ResponseHandler $handler;

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
        $this->handler = new ResponseHandler();
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
    public function getResponse(string $urlPath): ResponseInterface
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
        $this->handler->handleResponse($response);
        return $response;
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
