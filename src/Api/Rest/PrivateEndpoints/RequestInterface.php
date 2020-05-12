<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints;

use Kobens\Core\Http\ResponseInterface;
use Kobens\Gemini\Exception;

interface RequestInterface
{
    /**
     * @param string $urlPath
     * @param array $payload
     * @param array $config
     * @param bool $autoRetry
     * @throws Exception
     * @throws Exception\InvalidResponseException
     * @throws Exception\ResourceMovedException
     * @throws \Kobens\Core\Exception\ConnectionException
     * @throws \Kobens\Core\Exception\Http\RequestTimeoutException
     * @return ResponseInterface
     */
    public function getResponse(string $urlPath, array $payload = [], array $config = [], bool $autoRetry = false): ResponseInterface;
}
