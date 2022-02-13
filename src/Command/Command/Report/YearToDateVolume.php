<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Report;

use Kobens\Gemini\Exchange\Currency\Pair;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Kobens\Gemini\Command\Traits\Output;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Multiply;

final class YearToDateVolume extends Command
{
    use Output;

    protected static $defaultName = 'report:ytd-volume';

    private Adapter $adapter;

    public function __construct(
        Adapter $adapter
    ) {
        $this->adapter = $adapter;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Reports USD totals on sales from USD quoted trading pairs.');
        $this->addArgument('year', InputArgument::OPTIONAL, 'Year to report on. Defaults to current year.', \date('Y'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        try {
            $this->reportTotals(
                $this->getTotals((int) $input->getArgument('year')),
                $output
            );
        } catch (\Throwable $e) {
            $this->writeError($e->getMessage(), $output);
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $this->writeError($e->getTraceAsString(), $output);
            }
        }
        return $exitCode;
    }

    private function reportTotals(array $totals, OutputInterface $output): void
    {
        $grandTotalAmount = '0';
        $grandTotalTransactions = 0;
        foreach ($totals as $symbol => $data) {
            $output->writeln(sprintf(
                "%s:\n\tAmount:\t%s\n\tTransactions:\t%d",
                $symbol,
                $data['amount'],
                $data['transactions']
            ));
            $grandTotalAmount = Add::getResult($grandTotalAmount, $data['amount']);
            $grandTotalTransactions += $data['transactions'];
        }
        $output->writeln(sprintf(
            "\nGrand Total Amount:\t%s\nGrand Total Transactions:\t%d",
            $grandTotalAmount,
            $grandTotalTransactions
        ));
    }

    private function getTotals(int $year): array
    {
        $totals = [];
        foreach (Pair::getAllInstances() as $pair) {
            if ($pair->getQuote()->getSymbol() === 'usd') {
                $totals[$pair->getBase()->getSymbol()] = $this->getTotal(
                    $pair->getSymbol(),
                    $year
                );
            }
        }
        return $totals;
    }

    private function getTotal(string $symbol, int $year): array
    {
        $result = [
            'transactions' => 0,
            'amount' => '0',
        ];
        foreach ($this->getTrades($symbol, $year) as $trade) {
            $result['amount'] = Add::getResult(
                $result['amount'],
                Multiply::getResult(
                    $trade->price,
                    $trade->amount
                )
            );
            ++$result['transactions'];
        }
        return $result;
    }

    private function getTrades(string $symbol, int $year): \Generator
    {
        $table = new TableGateway('trade_history_' . $symbol, $this->adapter);
        $trade = $this->getFirstTrade($table, $year);
        if ($trade instanceof \ArrayObject) {
            yield $trade;
            while ($trade instanceof \ArrayObject) {
                $trades = $this->getNextBatch($table, (int) $trade->tid, $year);
                $trade = null;
                foreach ($trades as $trade) {
                    yield $trade;
                }
            }
        }
    }

    private function getNextBatch(TableGateway $table, int $lastTransactionId, int $year): ResultSetInterface
    {
        return $table->select(function (Select $select) use ($lastTransactionId, $year): void {
            $select->where->greaterThan('tid', $lastTransactionId);
            $select->where->lessThan('trade_date', ($year + 1) . '-01-01 00:00:00');
//             $select->where->equalTo('type', 'sell');
            $select->order('tid ASC');
            $select->limit(10000);
        });
    }

    private function getFirstTrade(TableGateway $table, int $year): ?\ArrayObject
    {
        $result = $table->select(function (Select $select) use ($year): void {
            $select->where->greaterThanOrEqualTo('trade_date', $year . '-01-01 00:00:00');
            $select->where->lessThan('trade_date', ($year + 1) . '-01-01 00:00:00');
//             $select->where->equalTo('type', 'sell');
            $select->order('tid ASC');
            $select->limit(1);
        });
        foreach ($result as $row) {
            return $row;
        }
        return null;
    }
}
