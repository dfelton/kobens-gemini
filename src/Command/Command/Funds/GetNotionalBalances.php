<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Funds;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalancesInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GetNotionalBalances extends Command
{
    protected static $defaultName = 'funds:notional-balances';

    private GetNotionalBalancesInterface $balances;

    public function __construct(
        GetNotionalBalancesInterface $getNotionalBalancesInterface
    ) {
        $this->balances = $getNotionalBalancesInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Returns available notional balances.');
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
                ['Amount Notional', $balance->getAmountNotional()],
                ['Available', $balance->getAvailable()],
                ['Available Notional', $balance->getAvailableNotional()],
                ['Available For Withdrawal', $balance->getAvailableForWithdrawal()],
                ['Available For Withdrawal Notional', $balance->getAvailableForWithdrawalNotional()],
            ]);
        $table->render();
    }
}