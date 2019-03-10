<?php

namespace Kobens\Gemini\Command\Command\Order;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\CancelAll as CancelAllOrders;
use Kobens\Gemini\Command\Traits\CommandTraits;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CancelAll extends Command
{
    use CommandTraits;

    protected static $defaultName = 'order:cancel-all';

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
                \Zend\Debug\Debug::dump($json);
            } elseif ($response->result === 'error') {
                $output->writeln([
                    'Response Code: '.$model->getResponseCode(),
                    'Error: '.$response->reason,
                    'Message: '.$response->message,
                ]);
            }
        }
    }

}