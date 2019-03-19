<?php

namespace Kobens\Gemini\Command\Command\Order\Placement;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\CancelAll as CancelAllOrders;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CancelAll extends Command
{
    protected function configure()
    {
        $this->setName('order:place:cancel-all');
        $this->setDescription('Cancel all open orders on the exchange.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = (new CancelAllOrders())->getResponse();
        $obj = \json_decode($response['body']);
        if ($obj->result === 'ok') {
            $output->writeln(\sprintf('%s order(s) cancelled.', \count($obj->details->cancelledOrders)));
            $output->writeln(\sprintf('%s order(s) cancellations rejected.', \count($obj->details->cancelRejects)));
        } else {
            $output->writeln([
                \sprintf("<fg=white;bg=red>HTTP Response Code:\t%s</>", $response['code']),
                \sprintf("<fg=white;bg=red>Result:\t\t\t%s</>", $obj->result),
                \sprintf("<fg=white;bg=red>Reaspon:\t\t%s</>", $obj->reason),
                \sprintf("<fg=white;bg=red>Message:\t\t%s</>", $obj->message),
            ]);
        }
    }

}