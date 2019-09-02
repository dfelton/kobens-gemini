<?php

namespace Kobens\Gemini\Command\Command\Taxes;

use Kobens\Core\Db;
use Kobens\Core\BinaryCalculator\Add;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

final class Form8949 extends Command
{
    protected static $defaultName = 'taxes:form8949';

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var TableGateway
     */
    private $table;

    protected function configure()
    {
        $this->addArgument('year', InputArgument::REQUIRED);
        $this->setDescription('Generates data for a US Federal Tax Form 8949.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bcadd = Add::getInstance();
        $capitalGains = '0';

        $i = 0;
        foreach ($this->getData($input) as $row) {
            if ($i===0 || $i % 50 === 0) {
                $this->outputHeaders($output);
            }

            $capitalGains = $bcadd->getResult($capitalGains, $row['capital_gain']);

            $output->write(\str_pad(\sprintf('%s Bitcoin', $row['amount']), 24, ' '));
            $output->write(\substr($row['buy_date'],0,10)."\t");
            $output->write(\substr($row['sell_date'],0,10)."\t");
            $output->write(\str_pad($row['proceeds'], 24, ' '));
            $output->write(\str_pad($row['cost_basis'], 24, ' '));
            $output->write($row['capital_gain']."\n");

            $i++;
        }

        $output->write("\n\n");
        $output->write(\sprintf("Total Capital Gains: %s\n", $capitalGains));
        $output->write(\sprintf("Total Line Items: %d", $i));
        $output->write("\n\n");
    }

    private function outputHeaders(OutputInterface $output): void
    {
        $output->write("\n\n");
        $output->write("<options=underscore>Description of Property</>\t");
        $output->write("<options=underscore>Date Acquired</>\t");
        $output->write("<options=underscore>Date Sold</>\t");
        $output->write("<options=underscore>Proceeds</>\t\t");
        $output->write("<options=underscore>Cost Basis</>\t\t");
        $output->write("<options=underscore>Gain or Loss</>\n");
    }

    private function getData(InputInterface $input): \Zend\Db\ResultSet\ResultSetInterface
    {
        return $this->getTable()->select(function(Select $select) use ($input)
        {
            $select->join(
                ['buyHistory' => 'trade_history_btcusd'],
                'buyHistory.tid = taxes_btcusd_sell_log.buy_tid',
                [
                    'buy_date' => 'trade_date',
                    'buy_price' => 'price'
                ]
            );
            $select->join(
                ['saleHistory' => 'trade_history_btcusd'],
                'saleHistory.tid = taxes_btcusd_sell_log.sell_tid',
                [
                    'sell_date' => 'trade_date',
                    'sell_price' => 'price',
                ]
            );
            $year = (int) $input->getArgument('year');
            $select->where->greaterThanOrEqualTo('saleHistory.trade_date', $year.'-01-01 00:00:00.000');
            $select->where->lessThan('saleHistory.trade_date', ($year + 1).'-01-01 00:00:00.000');
            $select->order([
                'taxes_btcusd_sell_log.sell_tid ASC',
                'taxes_btcusd_sell_log.buy_tid ASC'
            ]);

        });
    }

    private function getTable(): TableGateway
    {
        if (!$this->table) {
            $this->table = new TableGateway('taxes_btcusd_sell_log', Db::getAdapter());
        }
        return $this->table;
    }
}
