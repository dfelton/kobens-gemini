<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

/**
 * This order will only remove liquidity from the order book.
 * It will fill whatever part of the order it can immediately, then cancel any remaining amount so that no part of the order is added to the order book.
 * If the order doesn't fully fill immediately, the response back from the API will indicate that the order has already been canceled ("is_cancelled": true in JSON).
 */
interface ImmediateOrCancelInterface extends NewOrderInterface
{

}
