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
        $data = \json_decode(
            (new GetOrderStatus($input->getArgument('order_id')))->getResponse()['body'],
            true
        );
        $keyLength = 0;
        foreach (\array_keys($data) as $key) {
            if (\strlen($key) > $keyLength) {
                $keyLength = \strlen($key);
            }
        }
        foreach ($data as $key => $val) {
            $key = \ucwords(\str_replace('_', ' ', $key));
            $key = str_pad($key, $keyLength, ' ', STR_PAD_RIGHT);
            if ($val !== []) {
                $output->writeln("$key\t{$this->getFormattedVal($val)}");
            }
        }
    }

    protected function getFormattedVal($val) : string
    {
        switch (true) {
            case $val === true:
                $str = "<fg=green>true</>";
                break;
            case $val === false;
                $str = "<fg=red>false</>";
                break;
            case \is_numeric($val);
                $str = "<fg=yellow>$val</>";
                break;
            case \is_array($val):
                $str = PHP_EOL;
                foreach ($val as $key => $value) {
                    $key = \ucwords(\str_replace('_', ' ', $key));
                    $str .= "\t$key: $value";
                }
                break;
            default:
                $str = "<fg=white>$val</>";
                break;
        }
        return $str;
    }
}