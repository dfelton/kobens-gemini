<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder;

/**
 * @see \Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\MakerOrCancelInterface
 *
 * Similar to MakerOrCancel command, ForceMaker places a 'maker-or-cancel' order on
 * the order books, only adding liquidity to the order book. However if order placement
 * results in a cancellation of the order, ForceMaker will inquire for current market
 * prices and re-attempt with adjusted values necessary (minimal increment / decrement
 * as allowed by the exchange to get off the opposing side of the order book) until
 * and order is successfully placed or in which case the maximum allowed iterations
 * have been reached.
 */
interface ForceMakerInterface extends NewOrderInterface
{

}
