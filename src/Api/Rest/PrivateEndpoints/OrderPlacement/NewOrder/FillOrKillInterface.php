<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

/**
 * This order will only remove liquidity from the order book.
 * It will fill the entire order immediately or cancel.
 * If the order doesn't fully fill immediately, the response back from the API will indicate that the
 * order has already been canceled ("is_cancelled": true in JSON).
 */
interface FillOrKillInterface extends NewOrderInterface
{

}
