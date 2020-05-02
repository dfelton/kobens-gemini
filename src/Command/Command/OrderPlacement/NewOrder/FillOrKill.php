<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderPlacement\NewOrder;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\FillOrKillInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\NewOrderInterface;

final class FillOrKill extends AbstractNewOrder
{
    protected static $defaultName = 'order-placement:new-order:fill-or-kill';

    private FillOrKillInterface $fillOrKill;

    public function __construct(
        FillOrKillInterface $fillOrKillInterface
    ) {
        $this->fillOrKill = $fillOrKillInterface;
        $this->setDescription('Places a new <fg=yellow>fill-o-kill</> order on the exchange.');
        parent::__construct();
    }

    protected function getNewOrderInterface(): NewOrderInterface
    {
        return $this->fillOrKill;
    }
}
