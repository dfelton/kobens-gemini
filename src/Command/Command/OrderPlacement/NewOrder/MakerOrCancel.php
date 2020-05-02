<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderPlacement\NewOrder;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\MakerOrCancelInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\NewOrderInterface;

final class MakerOrCancel extends AbstractNewOrder
{
    protected static $defaultName = 'order-placement:new-order:maker-or-cancel';

    private MakerOrCancelInterface $makerOrCancel;

    public function __construct(
        MakerOrCancelInterface $makerOrCancelInterface
    ) {
        $this->makerOrCancel = $makerOrCancelInterface;
        $this->setDescription('Places a new <fg=yellow>maker-or-cancel</> order on the exchange.');
        parent::__construct();
    }

    protected function getNewOrderInterface(): NewOrderInterface
    {
        return $this->makerOrCancel;
    }
}
