<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints;

use Kobens\Core\Exception\Http\CurlException;
use Kobens\Core\Http\CurlInterface;
use Kobens\Core\Http\ResponseInterface;
use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Gemini\Exception;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Api\KeyInterface;
use Kobens\Gemini\Api\NonceInterface;
use Kobens\Gemini\Api\Helper\ResponseHandler;
use Psr\Log\LoggerInterface;
use Kobens\Gemini\Exception\MaxIterationsException;

/**
 * Class Request
 * @package Kobens\Gemini\Api\Rest\PrivateEndpoints
 */
final class Request implements RequestInterface
{
    private const MAX_ITERATIONS = 10;

    private KeyInterface $key;

    private NonceInterface $nonce;

    private ThrottlerInterface $throttler;

    private HostInterface $host;

    private ResponseHandler $responseHandler;

    private CurlInterface $curl;

    private LoggerInterface $logger;

    /**
     * Request constructor.
     * @param HostInterface $hostInterface
     * @param ThrottlerInterface $throttlerInterface
     * @param KeyInterface $keyInterface
     * @param NonceInterface $nonceInterface
     * @param CurlInterface $curlInterface
     */
    public function __construct(
        HostInterface $hostInterface,
        ThrottlerInterface $throttlerInterface,
        KeyInterface $keyInterface,
        NonceInterface $nonceInterface,
        CurlInterface $curlInterface,
        LoggerInterface $loggerInterface
    ) {
        $this->host = $hostInterface;
        $this->key = $keyInterface;
        $this->nonce = $nonceInterface;
        $this->throttler = $throttlerInterface;
        $this->responseHandler = new ResponseHandler();
        $this->curl = $curlInterface;
        $this->logger = $loggerInterface;
    }

    /**
     * FIXME: Re-assess where to put $this->responseHandler->handleResponse().
     * It is probably better to reside directly in the try statement, and we
     * don't throw an exception ourselves, but catch more types. Also, ResponseHandler
     * should throw something else for 500 range response codes.
     *
     *
     * @param string $urlPath
     * @param array $payload
     * @param array $config
     * @param bool $autoRetry
     * @return ResponseInterface
     * @throws CurlException
     * @throws Exception
     * @throws Exception\InvalidResponseException
     * @throws Exception\ResourceMovedException
     * @throws \Kobens\Core\Exception\ConnectionException
     * @throws \Kobens\Core\Exception\Http\RequestTimeoutException
     */
    public function getResponse(string $urlPath, array $payload = [], array $config = [], bool $autoRetry = false): ResponseInterface
    {
        $this->throttler->throttle();

        $config[CURLOPT_CONNECTTIMEOUT] = $config[CURLOPT_CONNECTTIMEOUT] ?? 60;
        $config[CURLOPT_TIMEOUT] = $config[CURLOPT_TIMEOUT] ?? 120;
        $config[CURLOPT_POST] = true;

        $response = null;
        $i = 0;
        do {
            ++$i;
            try {
                $config[CURLOPT_HTTPHEADER] = $this->getRequestHeaders($urlPath, $payload);
                $response = $this->curl->request(
                    'https://' . $this->host->getHost() . $urlPath,
                    $config
                );
            } catch (CurlException $e) {
                if (!$autoRetry) {
                    throw $e;
                }
                $this->logger->warning(\implode(
                    PHP_EOL,
                    [
                        $e->getMessage(),
                        $e->getTraceAsString(),
                    ]
                ));
            }
        } while (!$response && $autoRetry && ++$i < self::MAX_ITERATIONS);
        if (!$response) {
            throw new MaxIterationsException('Max Iterations Reached');
        }
        $this->responseHandler->handleResponse($response);
        return $response;
    }

    /**
     * @param string $urlPath
     * @param array $payload
     * @return array|string[]
     */
    final private function getRequestHeaders(string $urlPath, array $payload): array
    {
        $base64Payload = \base64_encode(\json_encode(\array_merge(
            $payload,
            [
                'request' => $urlPath,
                'nonce' => $this->nonce->getNonce(),
            ]
        )));
        return [
            'cache-control:no-cache',
            'content-length:0',
            'content-type:text/plain',
            'x-gemini-apikey:' . $this->key->getPublicKey(),
            'x-gemini-payload:' . $base64Payload,
            'x-gemini-signature:' . \hash_hmac('sha384', $base64Payload, $this->key->getSecretKey()),
        ];
    }
}
