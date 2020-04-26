<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api;

interface NonceInterface
{
    /**
     * Return a nonce for the API request
     *
     * @return string
     */
    public function getNonce(): string;
}
