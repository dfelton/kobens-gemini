<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;

/**
 * This order will only remove liquidity from the order book.
 * It will fill whatever part of the order it can immediately, then cancel any remaining amount so that no part of the order is added to the order book.
 * If the order doesn't fully fill immediately, the response back from the API will indicate that the order has already been canceled ("is_cancelled": true in JSON).
 */
final class ImmediateOrCancel extends AbstractNewOrder implements ImmediateOrCancelInterface
{
    public function place(PairInterface $pair, string $side, string $amount, string $price, string $clientOrderId = null): \stdClass
    {
        $this->payload = [
            'type' => 'exchange limit',
            'options' => ['immediate-or-cancel'],
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
