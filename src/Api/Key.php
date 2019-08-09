<?php

namespace Kobens\Gemini\Api;

use Kobens\Core\Config;

class Key implements KeyInterface
{
    /**
     * @var string
     */
    private $keyPublic;

    /**
     * @var string
     */
    private $keySecret;

    public function __construct()
    {
        $config = Config::getInstance()->get('gemini')->api->key;
        $this
            ->setPublicKey($config->public_key)
            ->setSecretKey($config->secret_key)
        ;
    }

    protected function setPublicKey(string $key): self
    {
        $key = \trim($key);
        if (\strlen($key) === 0) {
            throw new \Exception('Public key cannot be empty.');
        }
        $this->keyPublic = $key;
        return $this;
    }

    protected function setSecretKey(string $secret): self
    {
        $secret = \trim($secret);
        if (\strlen($secret) === 0) {
            throw new \Exception('Secret key cannot be empty.');
        }
        $this->keySecret = $secret;
        return $this;
    }

    public function getPublicKey(): string
    {
        return $this->keyPublic;
    }

    public function getSecretKey(): string
    {
        return $this->keySecret;
    }
}
