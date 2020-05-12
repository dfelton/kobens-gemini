<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;

/**
 * This order will only remove liquidity from the order book.
 * It will fill whatever part of the order it can immediately, then cancel any remaining amount so that no part of the order is added to the order book.
 * If the order doesn't fully fill immediately, the response back from the API will indicate that the order has already been canceled ("is_cancelled": true in JSON).
 */
final class ImmediateOrCancel implements ImmediateOrCancelInterface
{
    private const URL_PATH = '';

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
            'options' => ['immediate-or-cancel'],
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
