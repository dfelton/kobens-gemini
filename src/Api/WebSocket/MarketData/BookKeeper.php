<?php

namespace Kobens\Gemini\Api\WebSocket\MarketData;

use Kobens\Core\Config;
use Kobens\Exchange\Book\Keeper\AbstractKeeper;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Exception\Api\WebSocket\SocketSequenceException;
use Kobens\Gemini\Exception\Exception;
use Kobens\Gemini\Exchange;

final class BookKeeper extends AbstractKeeper
{
    private const API_PATH = '/v1/marketdata/';

    /**
     * @var int
     */
    private $socketSequence;

    /*protected $params = [
        'heartbeat'=> 'true',
        'trades'   => 'false',
        'auctions' => 'false',
        'bids'     => 'true',
        'offers'   => 'true'
    ];*/

    private const PARAMS = [
        'heartbeat'=> 'true',
        'trades'   => 'false',
        'auctions' => 'false',
        'bids'     => 'true',
        'offers'   => 'true'
    ];

    public function __construct(string $pairKey)
    {
        parent::__construct(new Exchange(), $pairKey);
    }

    /**
     * @throws Exception
     */
    public function openBook(): void
    {
        $this->ensureIsClosed();
        \Amp\Loop::run($this->getRunClosure());
    }

    private function ensureIsClosed(): void
    {
        try {
            $this->util->checkPulse();
            throw new Exception('Can only open closed book.');
        } catch (ClosedBookException $e) { }
    }

    /**
     * @return \Closure
     */
    private function getRunClosure(): \Closure
    {
        $websocketUrl = $this->getWebSocketUrl();
        return function () use ($websocketUrl)
        {
            /** @var \Amp\Websocket\Connection $connection */
            /** @var \Amp\Websocket\Message $message */
            $connection = yield \Amp\Websocket\Client\connect($websocketUrl);
            while ($message = yield $connection->receive()) {
                $payload = yield $message->buffer();
                $payload = \json_decode($payload);
                $this->processMessage($payload);
                $this->setPulse();
            }
        };
    }

    /**
     * @param \stdClass $payload
     * @throws SocketSequenceException
     */
    private function processMessage(\stdClass $payload): void
    {
        if ($payload->socket_sequence === 0) {
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
        for ($i = 0, $j = \count(self::PARAMS); $i < $j; $i++) {
            $str .= \array_keys(self::PARAMS)[$i] . '=' . \array_values(self::PARAMS)[$i] . '&';
        }
        return \rtrim($str, '&');
    }

}
