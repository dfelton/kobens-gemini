<?php

namespace Kobens\Gemini\Api;

use \Kobens\Gemini\Api\Host;

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
    
    /**
     * @param string $host
     * @param string $public
     * @param string $secret
     */
    public function __construct(\Zend\Config\Config $config)
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
        if (!in_array($host, [Host::PRODUCTION, Host::SANDBOX])) {
            throw new \Exception(sprintf('Invalid API Host "%s"', $host));
        }
        $this->host = $host;
        return $this;
    }
    
    protected function setPublicKey(string $key) : self
    {
        $key = trim($key);
        if (strlen($key) === 0) {
            throw new \Exception('Public key cannot be empty.');
        }
        $this->keyPublic = $key;
        return $this;
    }
    
    protected function setSecretKey(string $secret) : self
    {
        $secret = trim($secret);
        if (strlen($secret) === 0) {
            throw new \Exception('Secret key cannot be empty.');
        }
        $this->keySecret = $secret;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     * @see \Kobens\Gemini\Api\KeyInterface::getHost()
     */
    final public function getHost() : string
    {
        return $this->host;
    }
    
    /**
     * {@inheritDoc}
     * @see \Kobens\Gemini\Api\KeyInterface::getPublicKey()
     */
    final public function getPublicKey() : string
    {
        return $this->keyPublic;
    }
    
    /**
     * {@inheritDoc}
     * @see \Kobens\Gemini\Api\KeyInterface::getSecretKey()
     */
    final public function getSecretKey() : string
    {
        return $this->keySecret;        
    }
}