<?php

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
