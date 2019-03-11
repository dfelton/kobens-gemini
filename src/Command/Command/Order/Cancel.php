<?php

namespace Kobens\Gemini\Command\Command\Order;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\Cancel as CancelOrder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class Cancel extends Command
{
    protected static $defaultName = 'order:cancel';

    protected function configure()
    {
        $this->setDescription('Cancel all open orders on the exchange.');
        $this->addArgument('order_id', InputArgument::REQUIRED, 'Order id on exchange.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cancel = new CancelOrder($input->getArgument('order_id'));
        $cancel->makeRequest();
        \Zend\Debug\Debug::dump($cancel->getResponse());
    }
}