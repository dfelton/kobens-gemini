<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints;

use Kobens\Core\Http\ResponseInterface;

interface RequestInterface
{
    public function getResponse(string $urlPath, array $payload = [], array $config = [], bool $autoRetry = false): ResponseInterface;
}
