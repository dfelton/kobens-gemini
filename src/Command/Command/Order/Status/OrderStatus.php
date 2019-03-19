<?php

namespace Kobens\Gemini\Command\Command\Order\Status;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Kobens\Gemini\Api\Rest\Request\Order\Status\OrderStatus as GetOrderStatus;

class OrderStatus extends Command
{
    protected function configure()
    {
        $this->setName('order:status:order-status');
        $this->setDescription('Fetch the status for an individual order');
        $this->addArgument('order_id', InputArgument::REQUIRED, 'Exchange order id to fetch data for');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = (new GetOrderStatus($input->getArgument('order_id')))->getResponse();
        // @todo better output formatting
        \Zend\Debug\Debug::dump($data);
    }
}