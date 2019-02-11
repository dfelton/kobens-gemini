<?php

namespace Kobens\Gemini\Api\Rest;

abstract class Request implements \Kobens\Core\Config\RuntimeInterface
{
    const REQUEST_URI = '';

    protected $app;

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var \stdClass
     */
    protected $response;

    /**
     * @var number
     */
    protected $responseCode;

    /**
     * @var \Kobens\Gemini\Api\KeyInterface
     */
    protected $restKey;

    /**
     * @var \Kobens\Gemini\Api\NonceInterface
     */
    protected $nonce;

    protected $runtimeArgOptions = [];

    /**
     * @param \Kobens\Gemini\Api\KeyInterface $restKey
     * @param array $payload
     */
    public function __construct(
        \Kobens\Core\App $app
    ) {
        $this->app = $app;
        $this->restKey = new \Kobens\Gemini\Api\Key($app->getConfig()->get('gemini')->get('api'));
        $this->nonce = new \Kobens\Gemini\Api\Nonce($app->getDb());
    }

    /**
     * @param array $payload
     * @return self
     */
    public function setPayload(array $payload) : Request
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Kobens\Core\Config\RuntimeInterface::getRuntimeArgOptions()
     */
    public function getRuntimeArgOptions() : array
    {
        return $this->runtimeArgOptions;
    }

    /**
     * {@inheritDoc}
     * @see \Kobens\Core\Config\RuntimeInterface::setRuntimeArgs()
     */
    public function setRuntimeArgs(array $args) : \Kobens\Core\Config\RuntimeInterface
    {
        $this->setPayload($args);
        return $this;
    }

    /**
     * @return array
     */
    protected function getHeaders() : array
    {
        $payload = \array_merge(
            ['request' => static::REQUEST_URI, 'nonce' => $this->nonce->getNonce()],
            $this->getPayload()
        );
        $base64Payload = \base64_encode(\json_encode($payload));
        return [
            'Cache-Control: no-cache',
            'Content-Length: 0',
            'Content-Type: text/plain',
            'X-GEMINI-APIKEY: ' . $this->restKey->getPublicKey(),
            'X-GEMINI-PAYLOAD: ' . $base64Payload,
            'X-GEMINI-SIGNATURE: ' . \hash_hmac('sha384', $base64Payload, $this->restKey->getSecretKey())
        ];
    }

    /**
     * @throws \Exception
     * @return self
     */
    final public function makeRequest() : self
    {
        if (!is_null($this->responseCode)) {
            throw new \Exception('Cannot place same request twice.');
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());

        $response = (string) curl_exec($ch);
        $this->responseCode = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        curl_close($ch);

        if ($this->responseCode === 0) {
            throw new \Kobens\Gemini\Exception\ConnectionException(sprintf(
                'Unable to establish a connection with "%s%"',
                $this->restKey->getHost()
            ));
        } else {
            if ($this->responseCode >= 200 && $this->responseCode < 300) {
                $this->response = json_decode($response);
            } elseif ($this->responseCode >= 300 && $this->responseCode < 400) {
                throw new \Kobens\Gemini\Exception\ResourceMovedException('Resource has moved permanently');
            } elseif ($this->responseCode >= 400 && $this->responseCode < 500) {
                $this->response = json_decode($response);
                throw new \Kobens\Gemini\Exception\InvalidResponseException(sprintf(
                    $this->response->message
                ));
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getUrl() : string
    {
        return 'https://'.$this->restKey->getHost().static::REQUEST_URI;
    }

    /**
     * @return \stdClass
     */
    public function getResponse() : \stdClass
    {
        if (!$this->response instanceof \stdClass) {
            throw new \Exception('Response not set.');
        }
        return $this->response;
    }

    /**
     * @return array
     */
    public function getPayload() : array
    {
        return $this->payload;
    }
}