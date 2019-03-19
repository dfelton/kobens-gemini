<?php

namespace Kobens\Gemini\Api\Rest;

use Kobens\Gemini\Api\{Host, Key, Nonce};
use Kobens\Gemini\Exception\{
    ConnectionException,
    InvalidResponseException,
    ResourceMovedException
};
use Kobens\Gemini\Exception\Api\InvalidSignatureException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Kobens\Core\Config;
use Kobens\Gemini\Exception\Exception;

/**
 * @todo going to want portions of this in kobens/kobens-core somehow; at a minimum inside kobens/kobens-exchange
 * @todo further abstract out flexibility for GET|POST|PUT|DELETE
 */
abstract class Request
{
    const REQUEST_URI = '';

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
    }

    public function getResponse() : array
    {
        return $this->makeRequest();
    }


    final private function getHeaders() : array
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

    final private function makeRequest() : array
    {
        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL, 'https://'.(new Host()).static::REQUEST_URI);
        \curl_setopt($ch, CURLOPT_POST, true);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $timer = -microtime(true);
        $response = (string) \curl_exec($ch);
        $timer += microtime(true);
        $this->logTimer->info($timer);
        unset($timer);

        $responseCode = (int) \curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        \curl_close($ch);

        $this->throwResponseException($response, $responseCode);

        return [
            'code' => $responseCode,
            'body' => $response,
        ];
    }

    protected function throwResponseException(string $response, int $responseCode) : void
    {
        switch (true) {
            case $responseCode === 0:
                throw new ConnectionException(\sprintf('Unable to establish a connection with "%s"', (new Host())));
            case $responseCode >= 300 && $responseCode < 400:
                throw new ResourceMovedException($response, $responseCode);
            case $responseCode >= 400 && $responseCode < 500:
                if ($responseCode === 408) {
                    throw new Exception('408 Request Time-out', 408);
                }
                $message = \json_decode($response);
                switch ($message->reason) {
                    case InvalidSignatureException::REASON:
                        throw new InvalidSignatureException($message->message, $responseCode);
                    default:
                        break;
                }
            case $responseCode >= 500:
                throw new InvalidResponseException($response, $responseCode);
            default:
                break;
        }
    }

}
