<?php

namespace Kobens\Gemini\Command\Command\Funds;

use Kobens\Gemini\Api\Rest\Request\Funds\Balances;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GetAvailableBalances extends Command
{
    protected static $defaultName = 'funds:balances';

    private $keyLabels = [
        'amount'                 => "Amount.....................",
        'available'              => "Available..................",
        'availableForWithdrawal' => "Available For Withdrawal...",
    ];

    protected function configure()
    {
        $this->setDescription('Returns available balances.');
        $this->addOption('currency', 'c', InputOption::VALUE_OPTIONAL, 'Currency to get funds of');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $balances = (new Balances())->getResponse()['body'];
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln($e->getTraceAsString());
            exit(1);
        }
        $currency = $input->getOption('currency');
        $balances = \json_decode($balances, true);
        foreach ($balances as $balance) {
            unset($balance['type']);
            if ($currency === null) {
                $this->outputBalance($output, $balance);
            } elseif (\strtoupper($currency) === $balance['currency']) {
                $this->outputBalance($output, $balance);
                break;
            }
        }
    }

    private function outputBalance(OutputInterface $output, array $balance): void
    {
        $output->writeln("<options=bold,underscore>${balance['currency']}</>");
        unset($balance['currency']);
        foreach ($balance as $key => $val) {
            $key = $this->keyLabels[$key];
            $output->writeln("$key$val");
        }
        $output->write(PHP_EOL);
    }
}