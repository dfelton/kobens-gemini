<?php

namespace Kobens\Gemini\Api\Rest;

use Kobens\Gemini\Api\{Host, Key, Nonce};
use Kobens\Gemini\Exception\{
    ConnectionException,
    InvalidResponseException,
    ResourceMovedException
};
use Kobens\Gemini\Exception\Api\{
    InsufficientFundsException,
    InvalidSignatureException
};

/**
 * @todo going to want portions of this in kobens/kobens-core somehow; at a minimum inside kobens/kobens-exchange
 * @todo further abstract out flexibility for GET|POST|PUT|DELETE
 */
abstract class Request
{
    const REQUEST_URI = '';

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var string
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

    public function __construct()
    {
        $this->restKey = new Key();
        $this->nonce = new Nonce();
    }

    protected function getHeaders() : array
    {
        $payload = \array_merge(
            ['request' => static::REQUEST_URI, 'nonce' => $this->nonce->getNonce()],
            $this->payload
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

        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        \curl_setopt($ch, CURLOPT_POST, true);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());

        $response = (string) \curl_exec($ch);
        $this->responseCode = (int) \curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        \curl_close($ch);

        if ($this->responseCode === 0) {
            throw new ConnectionException(\sprintf(
                'Unable to establish a connection with "%s%"',
                (new Host())
            ));
        } else {
            $this->response = $response;
            if ($this->responseCode >= 200 && $this->responseCode < 300) {

            } elseif ($this->responseCode >= 300 && $this->responseCode < 400) {
                throw new ResourceMovedException($this->response);
            } elseif ($this->responseCode >= 400 && $this->responseCode < 500) {
                $message = \json_decode($response);
                switch ($message->reason) {
                    case InsufficientFundsException::REASON:
                        throw new InsufficientFundsException($message->message, $this->responseCode);
                        break;
                    case InvalidSignatureException::REASON:
                        throw new InvalidSignatureException($message->message, $this->responseCode);
                        break;
                    default:
                        throw new InvalidResponseException($this->response, $this->responseCode);
                        break;
                }
            } elseif ($this->responseCode >= 500) {
                throw new InvalidResponseException($this->response, $this->responseCode);
            }
        }

        return $this;
    }

    public function getResponse() : string
    {
        if ($this->response === null) {
            throw new \Exception('Response not set.');
        }
        return $this->response;
    }

    public function getResponseCode() : int
    {
        return $this->responseCode;
    }

    protected function getUrl() : string
    {
        return 'https://'.(new Host()).static::REQUEST_URI;
    }


}