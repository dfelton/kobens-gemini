<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;

/**
 * This order will only add liquidity to the order book.
 * If any part of the order could be filled immediately, the whole order will instead be canceled before any execution occurs.
 * If that happens, the response back from the API will indicate that the order has already been canceled ("is_cancelled": true in JSON).
 * Note: some other exchanges call this option "post-only".
 */
final class MakerOrCancel extends AbstractNewOrder implements MakerOrCancelInterface
{
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
        return \json_decode($this->getResponse()->getBody());
    }
}
