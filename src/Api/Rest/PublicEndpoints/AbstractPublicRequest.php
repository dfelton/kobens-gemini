<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

use Kobens\Gemini\Api\Rest\AbstractRequest;

abstract class AbstractPublicRequest extends AbstractRequest
{
    protected const CURL_POST = false;

    final protected function getRequestHeaders(): array
    {
        return [
            'cache-control:no-cache',
            'content-length:0',
            'content-type:text/plain',
        ];
    }
}
