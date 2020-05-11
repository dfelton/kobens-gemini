<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

use Kobens\Core\Http\ResponseInterface;

interface RequestInterface
{
    public function makeRequest(string $urlPath): ResponseInterface;
}
