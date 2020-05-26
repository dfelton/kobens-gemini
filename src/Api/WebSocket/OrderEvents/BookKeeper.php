<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\WebSocket\OrderEvents;

use Amp\Loop;
use Amp\Websocket\Client\Handshake;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Api\KeyInterface;
use Kobens\Gemini\Api\NonceInterface;
use Zend\Cache\Storage\StorageInterface;

/**
 * TODO: Finish me
 */
final class BookKeeper implements BookKeeperInterface
{
    private const REQUEST_URI = '/v1/order/events';

    protected StorageInterface $cache;

    private HostInterface $host;

    private KeyInterface $key;

    private NonceInterface $nonce;

    public function __construct(
        HostInterface $hostInterface,
        NonceInterface $nonceInterface,
        KeyInterface $keyInterface,
        StorageInterface $storageInterface
    ) {
        $this->host = $hostInterface;
        $this->nonce = $nonceInterface;
        $this->key = $keyInterface;
        $this->cache = $storageInterface;
    }

    public function openBook(): void
    {
        Loop::run(function () {
            /** @var \Amp\Websocket\Client\Connection $connection */
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

    protected function getUrl(): string
    {
        return 'wss://' . $this->host->getHost() . self::REQUEST_URI;
    }

    protected function getHeaders(): array
    {
        $payload = [
            'request' => self::REQUEST_URI,
            'nonce' => $this->nonce->getNonce()
        ];
        $base64Payload = \base64_encode(\json_encode($payload));
        $signature = \hash_hmac('sha384', $base64Payload, $this->key->getSecretKey());
        return [
            'X-GEMINI-APIKEY'    => $this->key->getPublicKey(),
            'X-GEMINI-PAYLOAD'   => $base64Payload,
            'X-GEMINI-SIGNATURE' => $signature,
        ];
    }
}
