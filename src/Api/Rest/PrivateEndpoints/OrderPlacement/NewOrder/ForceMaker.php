<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Exchange\ExchangeInterface;
use Kobens\Exchange\PairInterface;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Api\KeyInterface;
use Kobens\Gemini\Api\NonceInterface;
use Kobens\Gemini\Api\Rest\PublicEndpoints\TickerInterface;
use Kobens\Gemini\Exception\MaxIterationsException;

final class ForceMaker extends AbstractNewOrder implements ForceMakerInterface
{
    private const MAX_ITERATIONS = 100;

    /**
     * @var ExchangeInterface
     */
    private $exchange;

    /**
     * @var TickerInterface
     */
    private $ticker;

    public function __construct(
        HostInterface $hostInterface,
        ThrottlerInterface $throttlerInterface,
        KeyInterface $keyInterface,
        NonceInterface $nonceInterface,
        ExchangeInterface $exchangeInterface,
        TickerInterface $tickerInterface
    ) {
        $this->exchange = $exchangeInterface;
        $this->ticker = $tickerInterface;
        parent::__construct($hostInterface, $throttlerInterface, $keyInterface, $nonceInterface);
    }

    public function place(PairInterface $pair, string $side, string $amount, string $price, string $clientOrderId = null): \stdClass
    {
        $this->payload = [
            'type' => 'exchange limit',
            'options' => ['maker-or-cancel'],
            'symbol' => $pair->getSymbol(),
            'amount' => $amount,
            'price'  => $price,
            'side'   => $side,
        ];
        if ($clientOrderId) {
            $this->payload['client_order_id'] = $clientOrderId;
        };

        $iterations = 0;
        $isPlaced = false;
        $orderData = null;
        do {
            ++$iterations;
            $response = $this->getResponse();
            $orderData = \json_decode($response['body']);
            if ($orderData->is_cancelled === true && $orderData->reason === 'MakerOrCancelWouldTake') {
                $this->payload['price'] = $this->getNewPrice($pair, $price);
            } elseif ($orderData->is_cancelled === true) {
                throw new \Exception($orderData->reason, null, new \Exception($response['body'], $response['code']));
            } else {
                $isPlaced = true;
            }
        } while ($isPlaced === false && $iterations < self::MAX_ITERATIONS);
        if ($isPlaced === false) {
            throw new MaxIterationsException(\sprintf('Maximum attempts of %d reached', self::MAX_ITERATIONS));
        }
        return $orderData;
    }

    private function getNewPrice(PairInterface $pair, string $priceLimit): string
    {
        try {
            $price = $this->getPriceViaBook($pair->getSymbol());
        } catch (ClosedBookException $e) {
            $price = $this->getPriceViaTicker($pair->getSymbol());
        }
        switch ($this->payload['side']) {
            case 'buy':
                // If lowest ask is above what we are willing to bid, maker will place if we act now
                if ( (float) $priceLimit < (float) $price['ask']) {
                    $newPrice = $priceLimit;
                } else {
                    // Get smallest decrement possible from current ask price
                    $newPrice = \bcsub($price['ask'], $pair->getMinPriceIncrement(), $pair->getQuote()->getScale());
                }
                break;
            case 'sell':
                // If highest bid is above what we are willing to ask, maker will place if we act now
                if ( (float) $priceLimit > (float) $price['bid']) {
                    $newPrice = $priceLimit;
                } else {
                    // Get smallest inrement possible from current bid price
                    $newPrice = \bcadd($price['bid'], $pair->getMinPriceIncrement(), $pair->getQuote()->getScale());
                }
                break;
            default:
                throw new \Exception('Invalid side.');
        }
        return $newPrice;
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
     * @throws ClosedBookException
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
