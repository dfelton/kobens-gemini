<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api;

final class Key implements KeyInterface
{
    private string $public;

    private string $secret;

    public function __construct(string $public, string $secret)
    {
        $this->public = $public;
        $this->secret = $secret;
    }

    public function getPublicKey(): string
    {
        return $this->public;
    }

    public function getSecretKey(): string
    {
        return $this->secret;
    }
}
