<?php

namespace Kobens\Gemini\Command\Command\Order\Status;

use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Rest\Request\Order\Status\ActiveOrders as ModelActiveOrders;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ActiveOrders extends Command
{
    protected static $defaultName = 'list-active';

    protected function configure()
    {
        $this->setDescription('List all active orders.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = (new ModelActiveOrders())->getResponse();

        if ($response['body'] === '[]') {
            $output->writeln(\sprintf(
                '<fg=red>There are currently no active orders on "%s."</>',
                (string) (new Host())
            ));
        } else {
            // @todo cleanup output
            \Zend\Debug\Debug::dump(\json_decode($response['body'], true));
        }
    }

}