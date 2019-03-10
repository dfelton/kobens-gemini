<?php

namespace Kobens\Gemini\Api\WebSocket\MarketData;

use Kobens\Core\Config;
use Kobens\Exchange\Book\Keeper\AbstractKeeper;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Exchange\ExchangeInterface;
use Kobens\Gemini\Exception\Exception;

class BookKeeper extends AbstractKeeper
{
    const API_PATH = '/v1/marketdata/';

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
        string $pairKey
    ) {
        parent::__construct($exchange, $pairKey);
    }

    public function openBook(): void
    {
        $this->ensureIsClosed();
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
            $connection = yield \Amp\Websocket\Client\connect($websocketUrl);
            while ($message = yield $connection->receive()) {
                $payload = yield $message->buffer();
                $payload = \json_decode($payload);
                $this->processMessage($payload);
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
                throw new Exception('Out of sequence message');
            }
            if ($payload->type === 'update') {
                foreach ($payload->events as $e) {
                    $this->updateBook($e->side, $e->price, $e->remaining);
                }
            }
        }
        $this->socketSequence = $payload->socket_sequence;
    }

    public function getWebSocketUrl() : string
    {
        $str = \sprintf(
            'wss://%s%s%s?',
            (new Config())->gemini->api->host,
            self::API_PATH,
            $this->pair->getPairSymbol()
        );
        for ($i = 0, $j = \count($this->params); $i < $j; $i++) {
            $str .= \array_keys($this->params)[$i] . '=' . \array_values($this->params)[$i] . '&';
        }
        return \rtrim($str, '&');
    }

}