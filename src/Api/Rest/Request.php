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

/**
 * @todo going to want portions of this in kobens/kobens-core somehow; at a minimum inside kobens/kobens-exchange
 * @todo further abstract out flexibility for GET|POST|PUT|DELETE
 */
abstract class Request
{
    const REQUEST_URI = '';
    const RATE_LIMIT = 6;

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
        $this->restKey = new Key();
        $this->nonce = new Nonce();
        $this->logTimer = new Logger(static::REQUEST_URI);
        $this->logTimer->pushHandler(new StreamHandler(
            \sprintf(
                '%s/var/log/curl_timers.log',
                (new Config())->getRoot()
            ),
            Logger::INFO
        ));
        $this->throttler = new Throttler(self::class);
        if ($this->throttler->getLimit(self::class) === null) {
            $this->throttler->addThrottle(self::class, self::RATE_LIMIT);
        }
    }

    public function getResponse() : array
    {
        $response = $this->_getResponse();
        $this->throwResponseException($response['body'], $response['code']);
        return $response;
    }

    final private function _getResponse() : array
    {
        $this->throttler->throttle();

        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL, 'https://'.(new Host()).static::REQUEST_URI);
        \curl_setopt($ch, CURLOPT_POST, true);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getRequestHeaders());
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

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

    final private function getRequestHeaders() : array
    {
        $base64Payload = \base64_encode(\json_encode(\array_merge(
            ['request' => static::REQUEST_URI, 'nonce' => $this->nonce->getNonce()],
            $this->payload
        )));
        return [
            'Cache-Control: no-cache',
            'Content-Length: 0',
            'Content-Type: text/plain',
            'X-GEMINI-APIKEY: ' . $this->restKey->getPublicKey(),
            'X-GEMINI-PAYLOAD: ' . $base64Payload,
            'X-GEMINI-SIGNATURE: ' . \hash_hmac('sha384', $base64Payload, $this->restKey->getSecretKey()),
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
