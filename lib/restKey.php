<?php

require_once 'restKeyInterface.php';

class RestKey implements RestKeyInterface
{
    protected $host;
    protected $publicKey;
    protected $secretKey;

    public function __construct($host, $secretKey, $publicKey)
    {
        $this->host = $host;
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getNonce()
    {
        $microtime = explode(' ', microtime());
        return $microtime[1].substr($microtime[0], 2);
    }
}