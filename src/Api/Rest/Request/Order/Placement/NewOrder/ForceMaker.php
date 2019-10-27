<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder;

use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Exchange\Exception\Order\MakerOrCancelWouldTakeException;
use Kobens\Gemini\Exchange;
use Kobens\Gemini\Api\Param\Amount;
use Kobens\Gemini\Api\Param\ClientOrderId;
use Kobens\Gemini\Api\Param\Price;
use Kobens\Gemini\Api\Param\Side;
use Kobens\Gemini\Api\Param\Symbol;
use Kobens\Gemini\Api\Rest\Request\Market\Ticker;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder;
use Kobens\Gemini\Exception\MaxIterationsException;

/**
 * FIXME There is too much ability to go wrong here via public setters in the parent.
 * Are we accepting concrete hard limits via the constructor or not?
 * Eliminate public setters or eliminate statefulness in the class.
 *
 * FIXME When comparing current market price to current "price" use original
 * value not latest set price.
 */
final class ForceMaker extends NewOrder
{
    private const MAX_ITERATIONS = 50;

    protected $defaultPayload = [
        'type' => 'exchange limit',
        'options' => ['maker-or-cancel'],
    ];

    /**
     * Price limit
     *
     * @var string
     */
    private $limit;

    /**
     * @var \Kobens\Currency\PairInterface
     */
    private $pair;

    public function __construct(
        Side $side,
        Symbol $symbol,
        Amount $amount,
        Price $price,
        ClientOrderId $clientOrderId
    ) {
        parent::__construct($side, $symbol, $amount, $price, $clientOrderId);
        $this->limit = $this->payload['price'];
        $this->pair = (new Exchange())->getPair($this->payload['symbol']);
    }

    public function getResponse(): array
    {
        $iterations = 0;
        $response = null;
        do {
            $iterations++;
            try {
                $response = parent::getResponse();
            } catch (MakerOrCancelWouldTakeException $e) {
                $this->updatePrice();
            }
        } while ($response === null && $iterations < self::MAX_ITERATIONS);
        if ($response === null) {
            throw new MaxIterationsException(\sprintf('Maximum iterations of %d reached', self::MAX_ITERATIONS));
        }
        return $response;
    }

    private function updatePrice(): void
    {
        try {
            $price = $this->getPriceViaBook();
        } catch (ClosedBookException $e) {
            $price = $this->getPriceViaTicker();
        }
        switch ($this->payload['side']) {
            case 'buy':
            case 'bid':
                // If lowest ask is above what we are willing to bid, maker will place if we act now
                if ( (float) $this->limit < (float) $price['ask']) {
                    $newPrice = $this->limit;
                } else {
                    // Get smallest decrement possible from current ask price
                    $newPrice = \bcsub($price['ask'], $this->pair->minPriceIncrement, $this->pair->getQuote()->getScale());
                }
                break;
            case 'sell':
            case 'ask':
                // If highest bid is above what we are willing to ask, maker will place if we act now
                if ( (float) $this->limit > (float) $price['bid']) {
                    $newPrice = $this->limit;
                } else {
                    // Get smallest inrement possible from current bid price
                    $newPrice = \bcadd($price['bid'], $this->pair->minPriceIncrement, $this->pair->getQuote()->getScale());
                }
                break;
            default:
                throw new \Exception('Invalid side.');
        }
        $this->setPrice(new Price($newPrice));
    }

    private function getPriceViaTicker(): array
    {
        $data = \json_decode((new Ticker($this->payload['symbol']))->getResponse()['body'], true);
        return [
            'bid' => $data['bid'],
            'ask' => $data['ask']
        ];
    }

    /**
     * @throws ClosedBookException
     */
    private function getPriceViaBook(): array
    {
        $book = (new Exchange())->getBook($this->payload['symbol']);
        return [
            'bid' => $book->getBidPrice(),
            'ask' => $book->getAskPrice()
        ];
    }

}
