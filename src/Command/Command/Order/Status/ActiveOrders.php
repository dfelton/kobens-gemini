<?php

namespace Kobens\Gemini\Command\Command\Order\Status;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Api\Rest\Request\Order\Status\ActiveOrders as ModelActiveOrders;
use Kobens\Gemini\Api\Host;

class ActiveOrders extends Command
{
    protected static $defaultName = 'order:status:active';

    protected function configure()
    {
        $this->setDescription('Cancel all open orders on the exchange.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $model = new ModelActiveOrders();
        $json = $model->makeRequest()->getResponse();

        if ($json === '[]') {
            $output->writeln(\sprintf(
                '<fg=red>There are currently no active orders on "%s."</>',
                (string) (new Host())
            ));
        } else {
            \Zend\Debug\Debug::dump(\json_decode($json, true));
        }
    }

}