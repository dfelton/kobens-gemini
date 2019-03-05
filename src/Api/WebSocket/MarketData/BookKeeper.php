<?php

namespace Kobens\Gemini\Api\WebSocket\MarketData;

use Kobens\Exchange\Book\Keeper\AbstractKeeper;
use Kobens\Gemini\Exception\Exception;
use Kobens\Exchange\Exception\ClosedBookException;

class BookKeeper extends AbstractKeeper
{
    const WEBSOCKET_URL = 'wss://api.gemini.com/v1/marketdata/:pair';

    /**
     * @var int
     */
    private $socketSequence;

    /**
     * @var array
     */
    protected $params = [
        'heartbeat'=> 'true',
        'trades'   => 'false',
        'auctions' => 'false',
        'bids' => 'true',
        'offers' => 'true'
    ];

    public function openBook(): void
    {
        $this->ensureIsClosed();
        $this->setPulse();
        \Amp\Loop::run($this->getRunClosure());
    }

    private function ensureIsClosed()
    {
        try {
            $this->util->checkPulse();
            throw new Exception('Can only open closed book.');
        } catch (ClosedBookException $e) { }
    }

    private function getRunClosure() : \Closure
    {
        $websocketUrl = $this->getWebSocketUrl();
        return function () use ($websocketUrl)
        {
            /** @var \Amp\Websocket\Connection $connection */
            /** @var \Amp\Websocket\Message $message */
            $connection = yield \Amp\Websocket\connect($websocketUrl);
            while ($message = yield $connection->receive()) {
                $payload = yield $message->buffer();
                $payload = \json_decode($payload);
                try {
                    $this->processMessage($payload);
                } catch (\Kobens\Gemini\Exception\Exception $e) {
                    // @todo
                } catch (\Exception $e) {
                    // @todo
                }
                $this->setPulse();
            }
        };
    }

    protected function processMessage(\stdClass $payload)
    {
        if ($payload->socket_sequence === 0) {
            $book = [];
            foreach ($payload->events as $e) {
                $book[$e->side][$e->price] = $e->remaining;
            }
            $this->populateBook($book);
        } else {
            if ($this->socketSequence !== $payload->socket_sequence - 1) {
                throw new \Kobens\Gemini\Exception\Exception('Out of sequence message');
            }
            if ($payload->type === 'update') {
                foreach ($payload->events as $e) {
                    $this->updateBook($e->side, $e->price, $e->remaining);
                }
            }
        }
        $this->socketSequence = $payload->socket_sequence;
    }

    protected function getWebSocketUrl() : string
    {
        $str = \str_replace(':pair', $this->pair->getPairSymbol(), self::WEBSOCKET_URL);
        $str .= '?';
        for ($i = 0, $j = \count($this->params); $i < $j; $i++) {
            $str .= \array_keys($this->params)[$i] . '=' . \array_values($this->params)[$i] . '&';
        }
        return \rtrim($str, '&');
    }

}