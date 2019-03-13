<?php

namespace Kobens\Gemini\Api\Websocket\OrderEvents;

use Amp\Loop;
use Amp\Websocket\Client\Handshake;
use Kobens\Core\Cache;
use Kobens\Gemini\Exchange;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Nonce;
use Kobens\Gemini\Api\Key;

class BookKeeper
{
    const REQUEST_URI = '/v1/order/events';

    /**
     * @var \Zend\Cache\Storage\StorageInterface
     */
    protected $cache;

    /**
     * @var \Kobens\Exchange\ExchangeInterface
     */
    protected $exchange;

    public function __construct()
    {
        $this->exchange = new Exchange();
        $this->cache = (new Cache())->getCache();
    }

    public function openBook() : void
    {
        Loop::run(function ()
        {
            /** @var \Amp\Websocket\Connection $connection */
            /** @var \Amp\Websocket\Message $message */
            $connection = yield \Amp\Websocket\Client\connect(
                new Handshake(
                    $this->getUrl(),
                    null, // @todo any useful Options to set here?
                    $this->getHeaders()
                )
            );
            while ($message = yield $connection->receive()) {
                $payload = yield $message->buffer();
                \Zend\Debug\Debug::dump($payload);
            }
        });
    }

    protected function getUrl()
    {
        return 'wss://'.(new Host())->getHost().static::REQUEST_URI;
    }

    protected function getHeaders() : array
    {
        $key = new Key();
        $payload = [
            'request' => static::REQUEST_URI,
            'nonce' => (new Nonce())->getNonce()
        ];
        $base64Payload = \base64_encode(\json_encode($payload));
        $signature = \hash_hmac('sha384', $base64Payload, $key->getSecretKey());
        return [
            'X-GEMINI-APIKEY'    => $key->getPublicKey(),
            'X-GEMINI-PAYLOAD'   => $base64Payload,
            'X-GEMINI-SIGNATURE' => $signature,
        ];
    }


}