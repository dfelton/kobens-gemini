<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;

/**
 * This order will only add liquidity to the order book.
 * If any part of the order could be filled immediately, the whole order will instead be canceled before any execution occurs.
 * If that happens, the response back from the API will indicate that the order has already been canceled ("is_cancelled": true in JSON).
 * Note: some other exchanges call this option "post-only".
 */
final class MakerOrCancel implements MakerOrCancelInterface
{
    private const URL_PATH = '/v1/order/new';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function place(PairInterface $pair, string $side, string $amount, string $price, string $clientOrderId = null): \stdClass
    {
        $payload = [
            'type' => 'exchange limit',
            'options' => ['maker-or-cancel'],
            'symbol' => $pair->getSymbol(),
            'amount' => $amount,
            'price'  => $price,
            'side'   => $side,
        ];
        if ($clientOrderId) {
            $payload['client_order_id'] = $clientOrderId;
        };
        $response = $this->request->getResponse(self::URL_PATH, $payload);
        return \json_decode($response->getBody());
    }
}
