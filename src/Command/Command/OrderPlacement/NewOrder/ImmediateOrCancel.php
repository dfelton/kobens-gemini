<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderPlacement\NewOrder;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\ImmediateOrCancelInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\NewOrderInterface;

final class ImmediateOrCancel extends AbstractNewOrder
{
    protected static $defaultName = 'order:new:immediate-or-cancel';

    private ImmediateOrCancelInterface $immediateOrCancel;

    public function __construct(
        ImmediateOrCancelInterface $immediateOrCancelInterface
    ) {
        $this->immediateOrCancel = $immediateOrCancelInterface;
        $this->setDescription('Places a new <fg=yellow>immediate-or-cancel</> order on the exchange.');
        parent::__construct();
    }

    protected function getNewOrderInterface(): NewOrderInterface
    {
        return $this->immediateOrCancel;
    }
}
