<?php

namespace Kobens\Gemini\Command\Command\OrderPlacement\NewOrder;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\NewOrderInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\StopLimitInterface;

final class StopLimit extends AbstractNewOrder
{
    protected static $defaultName = 'order-placement:new-order:stop-limit';

    /**
     * @var StopLimitInterface
     */
    private $limitOrder;

    public function __construct(
        StopLimitInterface $limitInterface
    ) {
        $this->limitOrder = $limitInterface;
        $this->setDescription('Places a new <fg=yellow>limit</> order on the exchange.');
        parent::__construct();
    }

    protected function getNewOrderInterface(): NewOrderInterface
    {
        return $this->limitOrder;
    }
}
