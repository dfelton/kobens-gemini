<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderPlacement\NewOrder;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\ForceMakerInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\NewOrderInterface;

final class ForceMaker extends AbstractNewOrder
{
    protected static $defaultName = 'order-placement:new-order:force-maker';

    /**
     * @var ForceMakerInterface
     */
    private $forceMaker;

    public function __construct(
        ForceMakerInterface $forceMakerInterface
    ) {
        $this->forceMaker = $forceMakerInterface;
        $this->setDescription('Forces a new <fg=yellow>maker-or-cancel</> order on the exchange (price adjusted if necessary).');
        parent::__construct();
    }

    protected function getNewOrderInterface(): NewOrderInterface
    {
        return $this->forceMaker;
    }
}
