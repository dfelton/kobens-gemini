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
                $this->setPulse();
                $payload = yield $message->buffer();
                $payload = \json_decode($payload, true);
                try {
                    $this->processMessage($payload);
                } catch (\Kobens\Gemini\Exception\Exception $e) {
                    // @todo
                } catch (\Exception $e) {
                    // @todo
                }

            }
        };
    }

    protected function processMessage(array $payload)
    {
        if ($payload['socket_sequence'] === 0) {
            $this->populateBook($payload['events']);
        } else {
            if ($this->socketSequence <> $payload['socket_sequence'] - 1) {
                throw new \Kobens\Gemini\Exception\Exception('Out of sequence message');
            }
            $this->socketSequence = $payload['socket_sequence'];
            switch ($payload['type']) {
                case 'heartbeat':
                    // @todo: Gemini recommends logging and retaining all heartbeat messages. If your WebSocket connection is unreliable, please contact Gemini support with this log.
                    // FIXME: If you miss one or more heartbeats, disconnect and reconnect.
                    break;
                case 'update':
                    $this->processEvents($payload['events'], $payload['timestampms']);
                    break;
                default:
                    throw new \Exception ('Unhandled Message Type: '.$payload['type']."\n");
                    break;
            }
        }
    }

    /**
     * Process a set of events for the market's order book.
     *
     * @param array $events
     * @param int $timestampms
     */
    protected function processEvents(array $events, int $timestampms) : void
    {
        foreach ($events as $event) {
            switch ($event['type']) {
                case 'change':
                    $this->updateBook($event['side'], $event['price'], $event['remaining']);
                    break;
                case 'trade':
                    break;
                    // FIXME: StorageInterface doesn't like objects, will need to resolve this
                    if (in_array($event['makerSide'], ['bid','ask'])) {
                        $this->setLastTrade(new \Kobens\Exchange\Book\Trade\Trade(
                            $event['makerSide'],
                            $event['amount'],
                            $event['price'],
                            $timestampms
                        ));
                    }
                    break;
                case 'auction_indicative':
                    // @todo
                    break;
                default:
                    // @todo throw exception?
                    break;
            }
        }
    }

    protected function getWebSocketUrl() : string
    {
        return \str_replace(':pair', $this->pair->getPairSymbol(), self::WEBSOCKET_URL);
    }

    /**
     * Populate the market's order book
     *
     * {@inheritDoc}
     * @see \Kobens\Exchange\Book\Keeper\AbstractKeeper::populateBook()
     */
    protected function populateBook(array $events) : void
    {
        if ($events[0]['reason'] !== 'initial') {
            throw new \Kobens\Gemini\Exception\Exception('Book can only be populated with initial event set');
        }
        $book = [
            'bid' => [],
            'ask' => []
        ];
        foreach ($events as $event) {
            $book[$event['side']][(string) $event['price']] = (string) $event['remaining'];
        }
        parent::populateBook($book);
    }

}