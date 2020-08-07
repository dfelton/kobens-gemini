<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderStatus;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class OrderStatus extends Command
{
    protected static $defaultName = 'order:status';

    private OrderStatusInterface $status;

    public function __construct(
        OrderStatusInterface $orderStatusInterface
    ) {
        $this->status = $orderStatusInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fetch the status for an individual order');
        $this->addOption('order_id', 'o', InputOption::VALUE_OPTIONAL, 'Order Id');
        $this->addOption('client_order_id', 'c', InputOption::VALUE_OPTIONAL, 'Client Order Id');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orderId = ((int) $input->getOption('order_id')) ?: null;
        $clientOrderId = $input->getOption('client_order_id');
        if ($orderId !== null && $clientOrderId !== null) {
            $output->writeln('<fg=red>Must provide either "order_id" or "client_order_id", not both.');
            return;
        } elseif ($orderId === null && $clientOrderId === null) {
            $output->writeln('<fg=red>Must provide either "order_id" or "client_order_id".');
            return;
        }

        $data = $orderId !== null
            ? $this->status->getStatus((int) $orderId)
            : $this->status->getStatusByClientOrderId($clientOrderId);


        if (!is_array($data)) {
            $data = [$data];
        } elseif (count($data) > 1) {
            $output->writeln(sprintf('Orders matching client_order_id "%s"', $clientOrderId));
        }

        foreach ($data as $order) {
            $order = \get_object_vars($order);

            $keyLength = 0;
            foreach (\array_keys($order) as $key) {
                if (\strlen($key) > $keyLength) {
                    $keyLength = \strlen($key);
                }
            }
            foreach ($order as $key => $val) {
                $key = \ucwords(\str_replace('_', ' ', $key));
                $key = \str_pad($key, $keyLength, ' ', STR_PAD_RIGHT);
                if ($val !== []) {
                    $output->writeln("$key\t{$this->getFormattedVal($val)}");
                }
            }
            $output->writeln(PHP_EOL);
        }
    }

    private function getFormattedVal($val): string
    {
        switch (true) {
            case $val === true:
                $str = "<fg=green>true</>";
                break;
            case $val === false:
                $str = "<fg=red>false</>";
                break;
            case \is_numeric($val):
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
