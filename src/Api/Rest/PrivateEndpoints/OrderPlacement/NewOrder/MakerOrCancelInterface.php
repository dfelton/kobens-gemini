<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

/**
 * This order will only add liquidity to the order book.
 * If any part of the order could be filled immediately, the whole order will instead be canceled before any execution occurs.
 * If that happens, the response back from the API will indicate that the order has already been canceled ("is_cancelled": true in JSON).
 * Note: some other exchanges call this option "post-only".
 */
interface MakerOrCancelInterface extends NewOrderInterface
{

}
