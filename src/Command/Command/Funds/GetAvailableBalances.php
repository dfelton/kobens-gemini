<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Funds;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalancesInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances\BalanceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GetAvailableBalances extends Command
{
    protected static $defaultName = 'funds:balances';

    private GetAvailableBalancesInterface $balances;

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
            $this->outputBalance($output, $this->balances->getBalance($currency));
        } else {
            foreach ($this->balances->getBalances() as $balance) {
                $this->outputBalance($output, $balance);
            }
        }
    }

    private function outputBalance(OutputInterface $output, BalanceInterface $balance): void
    {
        $table = new Table($output);
        $table
            ->setHeaders([new TableCell("<options=bold,underscore>{$balance->getCurrency()}</>", ['colspan' => 2])])
            ->setRows([
                ['Amount', $balance->getAmount()],
                ['Available', $balance->getAvailable()],
                ['Available For Withdrawal', $balance->getAvailableForWithdrawal()],
            ]);
        $table->render();
    }
}
