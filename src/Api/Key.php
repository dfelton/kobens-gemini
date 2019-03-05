<?php

namespace Kobens\Gemini\Api;

use Zend\Config\Config;

class Key implements KeyInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $keyPublic;

    /**
     * @var string
     */
    private $keySecret;

    public function __construct(Config $config)
    {
        $this->setHost($config->host);
        $this->setPublicKey($config->public_key);
        $this->setSecretKey($config->secret_key);
    }

    /**
     * @param string $host
     * @throws \Exception
     * @return self
     */
    protected function setHost(string $host) : self
    {
        if (!\in_array($host, [Host::PRODUCTION, Host::SANDBOX])) {
            throw new \Exception(\sprintf('Invalid API Host "%s"', $host));
        }
        $this->host = $host;
        return $this;
    }

    protected function setPublicKey(string $key) : self
    {
        $key = \trim($key);
        if (\strlen($key) === 0) {
            throw new \Exception('Public key cannot be empty.');
        }
        $this->keyPublic = $key;
        return $this;
    }

    protected function setSecretKey(string $secret) : self
    {
        $secret = \trim($secret);
        if (\strlen($secret) === 0) {
            throw new \Exception('Secret key cannot be empty.');
        }
        $this->keySecret = $secret;
        return $this;
    }

    public function getHost() : string
    {
        return $this->host;
    }

    public function getPublicKey() : string
    {
        return $this->keyPublic;
    }

    public function getSecretKey() : string
    {
        return $this->keySecret;
    }
}