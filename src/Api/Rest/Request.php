<?php

namespace Kobens\Gemini\Api\Rest;

use Kobens\Core\Config;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Core\Http\Request\Throttler;
use Kobens\Gemini\Api\{Host, Key, Nonce};
use Kobens\Gemini\Exception\{
    Exception,
    InvalidResponseException,
    ResourceMovedException
};
use Kobens\Gemini\Exception\Api\InvalidSignatureException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Kobens\Gemini\Exception\LogicException;

/**
 * @todo going to want portions of this in kobens/kobens-core somehow; at a minimum inside kobens/kobens-exchange
 * @todo further abstract out flexibility for GET|POST|PUT|DELETE
 */
abstract class Request
{
    const REQUEST_URI = '';

    const CURLOPT_CONNECTTIMEOUT = 10;
    const CURLOPT_RETURNTRANSFER = true;
    const CURLOPT_POST = true;

    /**
     * @var Logger
     */
    protected $logTimer;

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var \Kobens\Gemini\Api\KeyInterface
     */
    private $restKey;

    /**
     * @var \Kobens\Gemini\Api\NonceInterface
     */
    private $nonce;

    /**
     * @var Throttler
     */
    private $throttler;

    public function __construct()
    {
        $config = new Config();
        $this->restKey = new Key();
        $this->nonce = new Nonce();
        $this->logTimer = new Logger(static::REQUEST_URI);
        $this->logTimer->pushHandler(new StreamHandler(
            \sprintf(
                '%s/var/log/curl_timers.log',
                $config->getRoot()
            ),
            Logger::INFO
        ));
        $this->throttler = new Throttler($config->gemini->api->host);
    }

    public function getResponse() : array
    {
        $response = $this->_getResponse();
        $this->throwResponseException($response['body'], $response['code']);
        if ($response['code'] !== 200) {
            throw new LogicException(\sprintf(
                'Response code 200 expected, "%d" received.',
                $response['code']
            ));
        }
        return $response;
    }

    protected function getUrlPath()
    {
        return static::REQUEST_URI;
    }

    final private function _getResponse() : array
    {
        $this->throttler->throttle();

        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL,            'https://'.(new Host()).$this->getUrlPath());
        \curl_setopt($ch, CURLOPT_HTTPHEADER,     $this->getRequestHeaders());
        \curl_setopt($ch, CURLOPT_POST,           static::CURLOPT_POST);
        \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, static::CURLOPT_CONNECTTIMEOUT);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, static::CURLOPT_RETURNTRANSFER);

        $timer = -\microtime(true);
        $response = (string) \curl_exec($ch);
        $timer += \microtime(true);
        $this->logTimer->info($timer);
        unset($timer);

        $responseCode = (int) \curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        \curl_close($ch);

        return [
            'code' => $responseCode,
            'body' => $response,
        ];
    }

    /**
     * @todo Don't need to supply x-gemini-* headers for public market data requests
     *
     * @return array
     */
    final private function getRequestHeaders() : array
    {
        $base64Payload = \base64_encode(\json_encode(\array_merge(
            ['request' => static::REQUEST_URI, 'nonce' => $this->nonce->getNonce()],
            $this->payload
        )));
        return [
            'cache-control:no-cache',
            'content-length:0',
            'content-type:text/plain',
            'x-gemini-apikey:' . $this->restKey->getPublicKey(),
            'x-gemini-payload:' . $base64Payload,
            'x-gemini-signature:' . \hash_hmac('sha384', $base64Payload, $this->restKey->getSecretKey()),
        ];
    }

    protected function throwResponseException(string $body, int $code) : void
    {
        switch (true) {
            case $code === 0:
                throw new ConnectionException(\sprintf('Unable to establish a connection with "%s"', (new Host())));
            case $code >= 300 && $code < 400:
                throw new ResourceMovedException($body, $code);
            case $code >= 400 && $code < 500:
                if ($code === 408) {
                    throw new Exception('408 Request Time-out', 408);
                }
                $message = \json_decode($body);
                switch ($message->reason) {
                    case InvalidSignatureException::REASON:
                        throw new InvalidSignatureException($message->message, $code);
                    default:
                        break;
                }
            case $code >= 500:
                throw new InvalidResponseException($body, $code);
            default:
                break;
        }
    }

}
