<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\PublicEndpoints;

use Kobens\Core\Exception\Http\CurlException;
use Kobens\Core\Http\CurlInterface;
use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Core\Http\ResponseInterface;
use Kobens\Gemini\Exception;
use Psr\Log\LoggerInterface;

final class Request
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
     * @throws Exception
     */
    public function makeRequest(string $urlPath): ResponseInterface
    {
        $i = 0;
        do {
            try {
                $this->throttler->throttle();
                return $this->curl->request($this->host->getHost() . $urlPath);
            } catch (CurlException $e) {
                $this->logger->warning(implode(
                    PHP_EOL,
                    [
                        $e->getMessage(),
                        $e->getTraceAsString()
                    ]
                ));
            }
        } while (++$i < self::MAX_ITERATIONS);
        throw new Exception('Max Iterations Reached');
    }
}
