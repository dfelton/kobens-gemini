<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\WebSocket\MarketData;

use Kobens\Core\Config;
use Kobens\Exchange\Book\Keeper\AbstractKeeper;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Exception\Api\WebSocket\SocketSequenceException;
use Kobens\Gemini\Exception\Api\WebSocket\BookAlreadyOpenException;

final class BookKeeper extends AbstractKeeper
{
    private const API_PATH = '/v1/marketdata/';

    private int $socketSequence = 0;

    protected array $params = [
        'heartbeat' => 'true',
        'trades'    => 'false',
        'auctions'  => 'false',
        'bids'      => 'true',
        'offers'    => 'true'
    ];

    public function openBook(): void
    {
        if ($this->isOpen() === true) {
            throw new BookAlreadyOpenException('Can only open closed book.');
        }
        \Amp\Loop::run($this->getRunClosure());
    }

    public function isOpen(): bool
    {
        try {
            $this->util->checkPulse();
        } catch (ClosedBookException $e) {
            return false;
        }
        return true;
    }

    private function getRunClosure(): \Closure
    {
        $websocketUrl = $this->getWebSocketUrl();
        return function () use ($websocketUrl) {
            /** @var \Amp\Websocket\Client\Rfc6455Connection $connection */
            /** @var \Amp\Websocket\Message $message */
            $connection = yield \Amp\Websocket\Client\connect($websocketUrl);
            while ($message = yield $connection->receive()) {
                $payload = yield $message->buffer();
                $payload = \json_decode($payload);
                try {
                    $this->processMessage($payload);
                    $this->setPulse();
                    if ($this->shutdown()) {
                        \Amp\Loop::stop();
                    }
                } catch (\Throwable $e) {
                    \Amp\Loop::stop();
                    $this->socketSequence = 0;
                    throw $e;
                }
            }
        };
    }

    private function shutdown(): bool
    {
        return file_exists(
            Config::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'kill_market_book'
        );
    }

    private function processMessage(\stdClass $payload)
    {
        if ($payload->socket_sequence === 0) {
            if ($this->socketSequence !== 0) {
                throw new SocketSequenceException(\sprintf(
                    'Expected sequence number "%s", received "%s".',
                    $this->socketSequence + 1,
                    $payload->socket_sequence
                ));
            }
            if (($payload->events ?? null) == null) {
                throw new \Exception('"events" property not present on message.', 0, new \Exception(json_encode($payload)));
            }
            $book = [];
            foreach ($payload->events as $e) {
                $book[$e->side][$e->price] = $e->remaining;
            }
            $this->populateBook($book);
        } else {
            if ($this->socketSequence !== $payload->socket_sequence - 1) {
                throw new SocketSequenceException(\sprintf(
                    'Expected sequence number "%s", received "%s".',
                    $this->socketSequence + 1,
                    $payload->socket_sequence
                ));
            }
            if ($payload->type === 'update') {
                foreach ($payload->events as $e) {
                    $this->updateBook($e->side, $e->price, $e->remaining);
                }
            }
        }
        $this->socketSequence = $payload->socket_sequence;
    }

    public function getWebSocketUrl(): string
    {
        $str = \sprintf(
            'wss://%s%s%s?',
            Config::getInstance()->get('gemini')->api->host,
            self::API_PATH,
            $this->pair->getSymbol()
        );
        for ($i = 0, $j = \count($this->params); $i < $j; $i++) {
            $str .= \array_keys($this->params)[$i] . '=' . \array_values($this->params)[$i] . '&';
        }
        return \rtrim($str, '&');
    }
}
