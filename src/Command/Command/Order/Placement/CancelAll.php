<?php

namespace Kobens\Gemini\Command\Command\Order\Placement;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\CancelAll as CancelAllOrders;
use Kobens\Gemini\Command\Traits\Traits;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CancelAll extends Command
{
    use Traits;

    protected static $defaultName = 'order:placement:cancel-all';

    protected function configure()
    {
        $this->setDescription('Cancel all open orders on the exchange.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $model = new CancelAllOrders();
        $json = $output->writeln($model->makeRequest()->getResponse());
        if (\strlen($json)) {
            $response = \json_decode($json);
            if ($response->result === 'ok') {
                $output->writeln('<fg=green>All orders cancelled.</>');
                \Zend\Debug\Debug::dump($json);
            } elseif ($response->result === 'error') {
                $output->writeln([
                    \sprintf("<fg=white;bg=red>Response Code:\t%s</>", $model->getResponseCode()),
                    \sprintf("<fg=white;bg=red>Error:\t%s</>", $response->reason),
                    \sprintf("<fg=white;bg=red>Message:\t%s</>", $response->message),
                ]);
            }
        }
    }

}