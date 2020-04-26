<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Funds;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalancesInterface;
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

    /**
     * @var GetAvailableBalancesInterface
     */
    private $balances;

    public function __construct(
        GetAvailableBalancesInterface $getAvailableBalancesInterface
    ) {
        $this->balances = $getAvailableBalancesInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Returns available balances.');
        $this->addOption('currency', 'c', InputOption::VALUE_OPTIONAL, 'Currency to get funds of');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currency = $input->getOption('currency');
        if ($currency) {
            $this->outputBalance($output, $this->balances->getCurrency($currency));
        } else {
            foreach ($this->balances->getBalances() as $balance) {
                $this->outputBalance($output, $balance);
            }
        }
    }

    private function outputBalance(OutputInterface $output, \stdClass $balance): void
    {
        $output->writeln("<options=bold,underscore>{$balance->currency}</>");
        foreach ($this->keyLabels as $key => $label) {
            $output->writeln("$label{$balance->{$key}}");
        }
        $output->write(PHP_EOL);
    }
}