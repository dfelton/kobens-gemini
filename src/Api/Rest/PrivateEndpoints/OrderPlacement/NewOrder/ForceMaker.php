<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Api\KeyInterface;
use Kobens\Gemini\Api\NonceInterface;
use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Exception\MaxIterationsException;

/**
 * @see \Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\MakerOrCancel
 *
 * Similar to MakerOrCancel command, ForceMaker places a 'maker-or-cancel' order on
 * the order books, only adding liquidity to the order book. However if order placement
 * results in a calcellation of the order, ForceMaker will inquire for current market
 * prices and re-attempt with adjusted values necessary (minimal increment / decrement
 * as allowed by the exchange to get off the opposing side of the order book) until
 * and order is successfully placed or in which case the maximum allowed iterations
 * have been reached.
 */
final class ForceMaker extends AbstractNewOrder implements ForceMakerInterface
{
    private const MAX_ITERATIONS = 100;

    /**
     * @var GetPriceInterface
     */
    private $getPrice;

    public function __construct(
        HostInterface $hostInterface,
        ThrottlerInterface $throttlerInterface,
        KeyInterface $keyInterface,
        NonceInterface $nonceInterface,
        GetPriceInterface $getPriceInterface
    ) {
        $this->getPrice = $getPriceInterface;
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
        switch ($this->payload['side']) {
            case 'buy':
                $ask = $this->getPrice->getAsk($pair->getSymbol());
                // If lowest ask is above what we are willing to bid, maker will place if we act now
                if ( (float) $priceLimit < (float) $ask) {
                    $newPrice = $priceLimit;
                } else {
                    // Get smallest decrement possible from current ask price
                    $newPrice = \bcsub($ask, $pair->getMinPriceIncrement(), $pair->getQuote()->getScale());
                }
                break;

            case 'sell':
                $bid = $this->getPrice->getBid($pair->getSymbol());
                // If highest bid is above what we are willing to ask, maker will place if we act now
                if ( (float) $priceLimit > (float) $bid) {
                    $newPrice = $priceLimit;
                } else {
                    // Get smallest inrement possible from current bid price
                    $newPrice = \bcadd($bid, $pair->getMinPriceIncrement(), $pair->getQuote()->getScale());
                }
                break;

            default:
                throw new \Exception('Invalid side.');
        }
        return $newPrice;
    }

}
