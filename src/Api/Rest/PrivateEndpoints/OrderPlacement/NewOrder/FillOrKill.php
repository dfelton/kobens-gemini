<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;

/**
 * This order will only remove liquidity from the order book.
 * It will fill the entire order immediately or cancel.
 * If the order doesn't fully fill immediately, the response back from the API will indicate that the
 * order has already been canceled ("is_cancelled": true in JSON).
 */
final class FillOrKill extends AbstractNewOrder implements FillOrKillInterface
{
    public function place(PairInterface $pair, string $side, string $amount, string $price, string $clientOrderId = null): \stdClass
    {
        $this->payload = [
            'type' => 'exchange limit',
            'options' => ['fill-or-kill'],
            'symbol' => $pair->getSymbol(),
            'amount' => $amount,
            'price'  => $price,
            'side'   => $side,
        ];
        if ($clientOrderId) {
            $this->payload['client_order_id'] = $clientOrderId;
        };
        return \json_decode($this->getResponse()['body']);
    }
}
