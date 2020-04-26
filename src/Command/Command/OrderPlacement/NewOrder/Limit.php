<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderPlacement\NewOrder;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\LimitInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\NewOrderInterface;

final class Limit extends AbstractNewOrder
{
    protected static $defaultName = 'order-placement:new-order:limit';

    /**
     * @var LimitInterface
     */
    private $limitOrder;

    public function __construct(
        LimitInterface $limitInterface
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
