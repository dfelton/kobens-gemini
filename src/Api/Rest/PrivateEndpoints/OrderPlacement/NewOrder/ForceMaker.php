<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;
use Kobens\Gemini\Exception\MaxIterationsException;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Subtract;

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
final class ForceMaker implements ForceMakerInterface
{
    private const URL_PATH = '/v1/order/new';
    private const MAX_ITERATIONS = 100;

    private GetPriceInterface $getPrice;

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface,
        GetPriceInterface $getPriceInterface
    ) {
        $this->getPrice = $getPriceInterface;
        $this->request = $requestInterface;
    }

    public function place(PairInterface $pair, string $side, string $amount, string $price, string $clientOrderId = null): \stdClass
    {
        $payload = [
            'type'    => 'exchange limit',
            'options' => ['maker-or-cancel'],
            'symbol'  => $pair->getSymbol(),
            'amount'  => $amount,
            'price'   => $price,
            'side'    => $side,
        ];
        if ($clientOrderId) {
            $payload['client_order_id'] = $clientOrderId;
        };

        $iterations = 0;
        $isPlaced = false;
        $orderData = null;
        do {
            ++$iterations;
            $response = $this->request->getResponse(self::URL_PATH, $payload);
            $orderData = \json_decode($response->getBody());
            if ($orderData->is_cancelled === true && $orderData->reason === 'MakerOrCancelWouldTake') {
                $payload['price'] = $this->getNewPrice($pair, $price, $side);
            } elseif ($orderData->is_cancelled === true) {
                throw new \Exception(
                    $orderData->reason,
                    null,
                    new \Exception($response->getBody(), $response->getResponseCode())
                );
            } else {
                $isPlaced = true;
            }
        } while ($isPlaced === false && $iterations < self::MAX_ITERATIONS);
        if ($isPlaced === false) {
            throw new MaxIterationsException(\sprintf('Maximum attempts of %d reached', self::MAX_ITERATIONS));
        }
        return $orderData;
    }

    private function getNewPrice(PairInterface $pair, string $priceLimit, string $side): string
    {
        switch ($side) {
            case 'buy':
                $ask = $this->getPrice->getAsk($pair->getSymbol());
                $newPrice = Compare::getResult($priceLimit, $ask) === Compare::LEFT_LESS_THAN
                    ? $priceLimit
                    : Subtract::getResult($ask, $pair->getMinPriceIncrement());
                break;

            case 'sell':
                $bid = $this->getPrice->getBid($pair->getSymbol());
                $newPrice = Compare::getResult($priceLimit, $bid) === Compare::LEFT_GREATER_THAN
                    ? $priceLimit
                    : Add::getResult($bid, $pair->getMinPriceIncrement());
                break;

            default:
                throw new \Exception('Invalid side.');
        }
        return $newPrice;
    }
}
