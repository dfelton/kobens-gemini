<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints;

use Kobens\Gemini\Api\Rest\ResponseInterface;

interface RequestInterface
{
    public function getResponse(): ResponseInterface;
}
