<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderPlacement\NewOrder;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\NewOrderInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractNewOrder extends Command
{
    abstract protected function getNewOrderInterface(): NewOrderInterface;

    final protected function configure(): void
    {
        $this->addArgument('side', InputArgument::REQUIRED, 'buy|sell');
        $this->addArgument('symbol', InputArgument::REQUIRED);
        $this->addArgument('amount', InputArgument::REQUIRED);
        $this->addArgument('price', InputArgument::REQUIRED);
        $this->addArgument('clientOrderId', InputArgument::OPTIONAL);
    }

    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->getNewOrderInterface()->place(
            Pair::getInstance($input->getArgument('symbol')),
            $input->getArgument('side'),
            $input->getArgument('amount'),
            $input->getArgument('price'),
            $input->getArgument('clientOrderId')
        );
        $data = \get_object_vars($data);

        $keyLength = 0;
        foreach (\array_keys($data) as $key) {
            if (\strlen($key) > $keyLength) {
                $keyLength = \strlen($key);
            }
        }

        foreach ($data as $key => $val) {
            $key = \ucwords(\str_replace('_', ' ', $key));
            $key = \str_pad($key, $keyLength, ' ', STR_PAD_RIGHT);
            if ($val !== []) {
                $output->writeln("$key\t{$this->getFormattedVal($val)}");
            }
        }
        return 0;
    }

    final private function getFormattedVal($value): string
    {
        switch (true) {
            case $value === true:
                $str = "<fg=green>true</>";
                break;

            case $value === false:
                $str = "<fg=red>false</>";
                break;

            case \is_numeric($value):
                $str = "<fg=yellow>$value</>";
                break;

            case \is_array($value):
                $str = PHP_EOL;
                foreach ($value as $key => $val) {
                    $key = \ucwords(\str_replace('_', ' ', $key));
                    $str .= "\t$key: $val";
                }
                break;

            default:
                $str = "<fg=white>$value</>";
                break;
        }
        return $str;
    }
}
