<?php

namespace Kobens\Gemini\Api\Market;

use Kobens\Core\Exception\ConnectionException;
use Kobens\Exchange\ExchangeInterface;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Api\Rest\PublicEndpoints\TickerInterface;

class GetPrice implements GetPriceInterface
{
    private const MAX_ATTEMPTS = 50;
    /**
     * @var ExchangeInterface
     */
    private $exchange;

    /**
     * @var TickerInterface
     */
    private $ticker;

    public function __construct(
        ExchangeInterface $exchangeInterface,
        TickerInterface $tickerInterface
    ) {
        $this->exchange = $exchangeInterface;
        $this->ticker = $tickerInterface;
    }

    public function getAsk(string $symbol): string
    {
        return $this->getData($symbol)['ask'];
    }

    public function getBid(string $symbol): string
    {
        return $this->getData($symbol)['bid'];
    }

    private function getData(string $symbol): array
    {
        $data = null;
        $i = 0;
        do {
            ++$i;
            try {
                $data = $this->getPriceViaBook($symbol);
            } catch (ClosedBookException $e) {
                try {
                    $data = $this->getPriceViaTicker($symbol);
                } catch (ConnectionException $e) {
                    if ($i === self::MAX_ATTEMPTS) {
                        throw new \Exception('Max Attempts Reached.', 0, $e);
                    }
                }
            }
        } while ($data === null && $i < self::MAX_ATTEMPTS);

        if ($data === null) {
            throw new \LogicException('Unable to fetch pricing data.');
        }

        return $data;
    }

    private function getPriceViaTicker(string $symbol): array
    {
        $data = $this->ticker->getData($symbol);
        return [
            'bid' => $data->bid,
            'ask' => $data->ask
        ];
    }

    /**
     * @throws \Kobens\Exchange\Exception\ClosedBookException
     */
    private function getPriceViaBook(string $symbol): array
    {
        $book = $this->exchange->getBook($symbol);
        return [
            'bid' => $book->getBidPrice(),
            'ask' => $book->getAskPrice()
        ];
    }

}
