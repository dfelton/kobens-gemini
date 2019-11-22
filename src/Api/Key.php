<?php

namespace Kobens\Gemini\Api;

final class Key implements KeyInterface
{
    /**
     * @var string
     */
    private $public;

    /**
     * @var string
     */
    private $secret;

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
