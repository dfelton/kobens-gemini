<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api;

interface NonceInterface
{
    /**
     * Return a nonce for the API request
     */
    public function getNonce(): string;
}
