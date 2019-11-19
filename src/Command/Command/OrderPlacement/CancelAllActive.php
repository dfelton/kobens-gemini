<?php

namespace Kobens\Gemini\Command\Command\OrderPlacement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelAllActiveOrdersInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CancelAllActive extends Command
{
    protected static $defaultName = 'order-placement:cancel-all';

    /**
     * @var CancelAllActiveOrdersInterface
     */
    private $cancel;

    public function __construct(
        CancelAllActiveOrdersInterface $cancelAllActiveOrdersInterface
    ) {
        $this->cancel = $cancelAllActiveOrdersInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Cancel all open orders on the Gemini exchange.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->cancel->cancelAll();
        $output->writeln([
            \sprintf('%s order(s) cancelled.', \count($data->details->cancelledOrders)),
            \sprintf('%s order(s) cancellations rejected.', \count($data->details->cancelRejects))
        ]);
    }
}
