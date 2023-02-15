<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Market;

use Kobens\Core\Exception\ConnectionException;
use Kobens\Exchange\ExchangeInterface;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Api\Market\GetPrice\Result;
use Kobens\Gemini\Api\Market\GetPrice\ResultInterface;
use Kobens\Gemini\Api\Rest\PublicEndpoints\TickerInterface;
use Kobens\Gemini\Exception\MaxIterationsException;

class GetPrice implements GetPriceInterface
{
    private const MAX_ATTEMPTS = 50;

    private ExchangeInterface $exchange;

    private TickerInterface $ticker;

    public function __construct(
        ExchangeInterface $exchangeInterface,
        TickerInterface $tickerInterface
    ) {
        $this->exchange = $exchangeInterface;
        $this->ticker = $tickerInterface;
    }

    public function getAsk(string $symbol): string
    {
        return $this->getResult($symbol)->getAsk();
    }

    public function getBid(string $symbol): string
    {
        return $this->getResult($symbol)->getBid();
    }

    public function getResult(string $symbol): ResultInterface
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
                        throw new MaxIterationsException();
                    }
                }
            }
        } while ($data === null && $i < self::MAX_ATTEMPTS);

        return new Result($data['bid'], $data['ask']);
    }

    private function getPriceViaTicker(string $symbol): array
    {
        $data = $this->ticker->getData($symbol);
        return [
            'bid' => $data->bid,
            'ask' => $data->ask
        ];
    }

    private function getPriceViaBook(string $symbol): array
    {
        $book = $this->exchange->getBook($symbol);
        return [
            'bid' => $book->getBidPrice(),
            'ask' => $book->getAskPrice()
        ];
    }
}
