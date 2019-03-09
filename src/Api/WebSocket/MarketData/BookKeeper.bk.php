<?php

namespace Kobens\Gemini\Api\WebSocket\MarketData;

use Kobens\Exchange\Book\Keeper\AbstractKeeper;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Exchange\ExchangeInterface;
use Kobens\Gemini\Exception\Exception;

class BookKeeper extends AbstractKeeper
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $path = '/v1/marketdata/';

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
        'bids'     => 'true',
        'offers'   => 'true'
    ];

    public function __construct(
        ExchangeInterface $exchange,
        string $pairKey,
        string $host
    ) {
        parent::__construct($exchange, $pairKey);
        $this->host = $host;
    }

    public function openBook(): void
    {
        $this->ensureIsClosed();
        \Amp\Loop::run($this->getRunClosure());
        \Zend\Debug\Debug::dump('I AM FREEEE!'); // This does not execute as we are in the loop
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
            \Zend\Debug\Debug::dump('foo'); // executes once

            /** @var \Amp\Websocket\Connection $connection */
            /** @var \Amp\Websocket\Message $message */
            $connection = yield \Amp\Websocket\connect($websocketUrl);
            while ($message = yield $connection->receive()) {

                \Zend\Debug\Debug::dump($message === null);
                $payload = yield $message->buffer();
                $payload = \json_decode($payload);
                $this->processMessage($payload);
                $this->setPulse();
            }
            \Zend\Debug\Debug::dump('bar'); // does not execute, even in event of network loss
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
        $str = 'wss://'.$this->host.$this->path.$this->pair->getPairSymbol().'?';
        for ($i = 0, $j = \count($this->params); $i < $j; $i++) {
            $str .= \array_keys($this->params)[$i] . '=' . \array_values($this->params)[$i] . '&';
        }
        return \rtrim($str, '&');
    }

}