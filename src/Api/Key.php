<?php

namespace Kobens\Gemini\Api;

use Kobens\Core\Config;

/**
 * Class Key
 * @package Kobens\Gemini\Api
 */
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

    /**
     * Key constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $config = Config::getInstance()->get('gemini')->api->key;
        $this
            ->setPublicKey($config->public_key)
            ->setSecretKey($config->secret_key)
        ;
    }

    /**
     * @param string $key
     * @return Key
     * @throws \Exception
     */
    protected function setPublicKey(string $key): self
    {
        $key = \trim($key);
        if ($key === '') {
            throw new \InvalidArgumentException('Public key cannot be empty.');
        }
        $this->keyPublic = $key;
        return $this;
    }

    /**
     * @param string $secret
     * @return Key
     * @throws \Exception
     */
    protected function setSecretKey(string $secret): self
    {
        $secret = \trim($secret);
        if ($secret === '') {
            throw new \InvalidArgumentException('Secret key cannot be empty.');
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