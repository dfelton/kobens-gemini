<?php

namespace Kobens\Gemini\Api\Rest;

use Kobens\Core\Config\RuntimeInterface;

abstract class Request implements RuntimeInterface
{
    const REQUEST_URI = '';

    /**
     * @var \Kobens\Core\App\ResourcesInterface
     */
    protected $appResources;

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

    public function __construct(
        \Kobens\Core\App\ResourcesInterface $appResources
    ) {
        $this->appResources = $appResources;
        $this->restKey = new \Kobens\Gemini\Api\Key($appResources->getConfig()->get('gemini')->get('api'));
        $this->nonce = new \Kobens\Gemini\Api\Nonce($appResources->getDb());
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

    public function getRuntimeArgOptions() : array
    {
        return $this->runtimeArgOptions;
    }

    public function setRuntimeArgs(array $args) : RuntimeInterface
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
        $signature = \hash_hmac('sha384', $base64Payload, $this->restKey->getSecretKey());
        return [
            'Cache-Control: no-cache',
            'Content-Length: 0',
            'Content-Type: text/plain',
            'X-GEMINI-APIKEY: ' . $this->restKey->getPublicKey(),
            'X-GEMINI-PAYLOAD: ' . $base64Payload,
            'X-GEMINI-SIGNATURE: ' . $signature
        ];
    }

    /**
     * @throws \Exception
     * @return self
     */
    final public function makeRequest() : self
    {
        if (!\is_null($this->responseCode)) {
            throw new \Exception('Cannot place same request twice.');
        }

        $ch = curl_init();

        \curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        \curl_setopt($ch, CURLOPT_POST, true);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());

        $response = (string) \curl_exec($ch);
        $this->responseCode = (int) \curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        \curl_close($ch);

        if ($this->responseCode === 0) {
            throw new \Kobens\Gemini\Exception\ConnectionException(sprintf(
                'Unable to establish a connection with "%s%"',
                $this->restKey->getHost()
            ));
        } else {
            if ($this->responseCode >= 200 && $this->responseCode < 300) {
                $this->response = \json_decode($response);
            } elseif ($this->responseCode >= 300 && $this->responseCode < 400) {
                throw new \Kobens\Gemini\Exception\ResourceMovedException('Resource has moved permanently');
            } elseif ($this->responseCode >= 400 && $this->responseCode < 500) {
                $this->response = \json_decode($response);
                throw new \Kobens\Gemini\Exception\InvalidResponseException(sprintf(
                    $this->response->message
                ));
            }
        }

        return $this;
    }

    protected function getUrl() : string
    {
        return 'https://'.$this->restKey->getHost().static::REQUEST_URI;
    }

    public function getResponse() : \stdClass
    {
        if (!$this->response instanceof \stdClass) {
            throw new \Exception('Response not set.');
        }
        return $this->response;
    }

    public function getPayload() : array
    {
        return $this->payload;
    }
}