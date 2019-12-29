<?php

namespace Kobens\Gemini\Command\Command\Taxes;

use Kobens\Core\Db;
use Kobens\Math\BasicCalculator\Add;
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
        $capitalGains = [
            'assetsSold' => '0',
            'totalProceeds' => '0',

            'short' => [],
            'long'  => [],
            'shortTotal' => '0',
            'longTotal'  => '0',
        ];

        $i = 0;
        foreach ($this->getData($input) as $row) {
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE && ($i===0 || $i % 50 === 0)) {
                $this->outputHeaders($output);
            }

            $capitalGains['assetsSold'] = Add::getResult($capitalGains['assetsSold'], $row['amount']);
            $capitalGains['totalProceeds'] = Add::getResult($capitalGains['totalProceeds'], $row['proceeds']);

            if ($this->isLongTerm($row['buy_date'], $row['sell_date'])) {
                $capitalGains['long'][] = $row;
                $capitalGains['longTotal'] = Add::getResult($capitalGains['longTotal'], $row['capital_gain']);


            } else {
                $capitalGains['short'][] = $row;
                $capitalGains['shortTotal'] = Add::getResult($capitalGains['shortTotal'], $row['capital_gain']);
            }

            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->write(\str_pad(\sprintf('%s Bitcoin', $row['amount']), 26, ' ', STR_PAD_RIGHT));
                $output->write(\str_pad(\substr($row['buy_date'],0,10),         16, ' ', STR_PAD_RIGHT));
                $output->write(\str_pad(\substr($row['sell_date'],0,10),        16, ' ', STR_PAD_RIGHT));
                $output->write(\str_pad($row['proceeds'],                       24, ' ', STR_PAD_RIGHT));
                $output->write(\str_pad($row['cost_basis'],                     24, ' ', STR_PAD_RIGHT));
                $output->write($row['capital_gain']."\n");
            }

            $i++;
        }

        $output->write("\n\n");
        $output->write(\sprintf("Total Short Term Capital Gains: %s\n", $capitalGains['shortTotal']));
        $output->write(\sprintf("Total Short Term Trades: %d", \count($capitalGains['short'])));
        $output->write("\n");
        $output->write(\sprintf("Total Long Term Capital Gains: %s\n", $capitalGains['longTotal']));
        $output->write(\sprintf("Total Long Term Trades: %d\n", \count($capitalGains['long'])));
        $output->write("\n");
        $output->write(\sprintf("Total Proceeds: %s\n", $capitalGains['totalProceeds']));
        $output->write(\sprintf("Total Assets Sold: %s\n", $capitalGains['assetsSold']));
        $output->write("\n");
    }

    private function isLongTerm(string $buyDate, string $sellDate): bool
    {
        $hodlDateStart = \strtotime('+1 days', \strtotime(\substr($buyDate, 0, 10)));
        $hodlDateStart = \strtotime('+1 years', $hodlDateStart);
        $sellDateStart = \strtotime(\substr($sellDate, 0, 10));
        return $hodlDateStart <= $sellDateStart;
    }

    private function outputHeaders(OutputInterface $output): void
    {
        $output->write("\n");
        $output->write(\str_pad("<options=underscore>Description of Property</>", 26+23, ' ', STR_PAD_RIGHT));
        $output->write(\str_pad("<options=underscore>Date Acquired</>",           16+23, ' ', STR_PAD_RIGHT));
        $output->write(\str_pad("<options=underscore>Date Sold</>",               16+23, ' ', STR_PAD_RIGHT));
        $output->write(\str_pad("<options=underscore>Proceeds</>",                24+23, ' ', STR_PAD_RIGHT));
        $output->write(\str_pad("<options=underscore>Cost Basis</>",              24+23, ' ', STR_PAD_RIGHT));
        $output->write(         "<options=underscore>Gain or Loss</>");
        $output->write("\n");
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
