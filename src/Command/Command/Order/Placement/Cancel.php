<?php

namespace Kobens\Gemini\Command\Command\Order\Placement;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\Cancel as CancelOrder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Cancel extends Command
{
    protected static $defaultName = 'order:cancel';

    protected function configure()
    {
        $this->setDescription('Cancel an individual open order on the Gemini exchange.');
        $this->addArgument('order_id', InputArgument::REQUIRED, 'Order id on exchange.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = (new CancelOrder($input->getArgument('order_id')))->getResponse();
        // @todo cleanup output
        $output->writeln($response['body']);
    }
}