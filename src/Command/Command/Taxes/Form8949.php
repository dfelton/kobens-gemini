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
use Symfony\Component\Console\Input\InputOption;
use Kobens\Gemini\Exchange\Currency\Pair;

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
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Trading Symbol', 'btcusd');
        $this->setDescription('Generates data for a US Federal Tax Form 8949.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->symbol = Pair::getInstance($input->getOption('symbol'))->getSymbol();
        $capitalGains = [
            'assetsSold'    => '0',
            'totalProceeds' => '0',

            'long_count'         => 0,
            'long_capital_gain'  => '0',
            'long_cost_basis'    => '0',
            'long_date_purchase' => null,
            'long_date_sale'     => null,
            'long_proceeds'      => '0',

            'short_count'         => 0,
            'short_capital_gain'  => '0',
            'short_cost_basis'    => '0',
            'short_date_purchase' => null,
            'short_date_sale'     => null,
            'short_proceeds'      => '0',
        ];

        $i = 0;
        foreach ($this->getData($input) as $row) {
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE && ($i===0 || $i % 50 === 0)) {
                $this->outputHeaders($output);
            }

            $capitalGains['assetsSold']    = Add::getResult($capitalGains['assetsSold'],    $row['amount']);
            $capitalGains['totalProceeds'] = Add::getResult($capitalGains['totalProceeds'], $row['proceeds']);

            if ($this->isLongTerm($row['buy_date'], $row['sell_date'])) {
                ++$capitalGains['long_count'];
                $capitalGains['long_capital_gain'] = Add::getResult($capitalGains['long_capital_gain'], $row['capital_gain']);
                $capitalGains['long_cost_basis']   = Add::getResult($capitalGains['long_cost_basis'],   $row['cost_basis']);
                $capitalGains['long_proceeds']     = Add::getResult($capitalGains['long_proceeds'],     $row['proceeds']);
                if ($capitalGains['long_date_purchase'] === null || \strtotime($capitalGains['long_date_purchase']) > \strtotime($row['buy_date'])) {
                    $capitalGains['long_date_purchase'] = $row['buy_date'];
                }
                if ($capitalGains['long_date_sale'] === null || \strtotime($capitalGains['long_date_sale']) < \strtotime($row['sell_date'])) {
                    $capitalGains['long_date_sale'] = $row['sell_date'];
                }
            } else {
                ++$capitalGains['short_count'];
                $capitalGains['short_capital_gain'] = Add::getResult($capitalGains['short_capital_gain'], $row['capital_gain']);
                $capitalGains['short_cost_basis']   = Add::getResult($capitalGains['short_cost_basis'],   $row['cost_basis']);
                $capitalGains['short_proceeds']     = Add::getResult($capitalGains['short_proceeds'],     $row['proceeds']);
                if ($capitalGains['short_date_purchase'] === null || \strtotime($capitalGains['short_date_purchase']) > \strtotime($row['buy_date'])) {
                    $capitalGains['short_date_purchase'] = $row['buy_date'];
                }
                if ($capitalGains['short_date_sale'] === null || \strtotime($capitalGains['short_date_sale']) < \strtotime($row['sell_date'])) {
                    $capitalGains['short_date_sale'] = $row['sell_date'];
                }
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

        $output->write("\n");
        $output->write("<options=bold>Short Term</>\n");
        $output->write(\sprintf("Trades:\t\t%d\n",      $capitalGains['short_count']));
        $output->write(\sprintf("Cost Basis:\t%s\n",    $capitalGains['short_cost_basis']));
        $output->write(\sprintf("Proceeds:\t%s\n",      $capitalGains['short_proceeds']));
        $output->write(\sprintf("Capital Gains:\t%s\n", $capitalGains['short_capital_gain']));
        $output->write(\sprintf("Date Purchase:\t%s\n", $capitalGains['short_date_purchase']));
        $output->write(\sprintf("Date Sell:\t%s\n",     $capitalGains['short_date_sale']));
        $output->write("\n");

        $output->write("<options=bold>Long Term</>\n");
        $output->write(\sprintf("Trades:\t\t%d\n",      $capitalGains['long_count']));
        $output->write(\sprintf("Cost Basis:\t%s\n",    $capitalGains['long_cost_basis']));
        $output->write(\sprintf("Proceeds:\t%s\n",      $capitalGains['long_proceeds']));
        $output->write(\sprintf("Capital Gains:\t%s\n", $capitalGains['long_capital_gain']));
        $output->write(\sprintf("Date Purchase:\t%s\n", $capitalGains['long_date_purchase']));
        $output->write(\sprintf("Date Sell:\t%s\n",     $capitalGains['long_date_sale']));
        $output->write("\n");

        $output->write(\sprintf("Total Proceeds:\t\t%s\n",  $capitalGains['totalProceeds']));
        $output->write(\sprintf("Total Assets Sold:\t%s\n", $capitalGains['assetsSold']));
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
                ['buyHistory' => "trade_history_{$this->symbol}"],
                "buyHistory.tid = taxes_{$this->symbol}_sell_log.buy_tid",
                [
                    'buy_date' => 'trade_date',
                    'buy_price' => 'price'
                ]
            );
            $select->join(
                ['saleHistory' => "trade_history_{$this->symbol}"],
                "saleHistory.tid = taxes_{$this->symbol}_sell_log.sell_tid",
                [
                    'sell_date' => 'trade_date',
                    'sell_price' => 'price',
                ]
            );
            $year = (int) $input->getArgument('year');
            $select->where->greaterThanOrEqualTo('saleHistory.trade_date', $year.'-01-01 00:00:00.000');
            $select->where->lessThan('saleHistory.trade_date', ($year + 1).'-01-01 00:00:00.000');
            $select->order([
                "taxes_{$this->symbol}_sell_log.sell_tid ASC",
                "taxes_{$this->symbol}_sell_log.buy_tid ASC"
            ]);
        });
    }

    private function getTable(): TableGateway
    {
        if (!$this->table) {
            $this->table = new TableGateway("taxes_{$this->symbol}_sell_log", Db::getAdapter());
        }
        return $this->table;
    }
}
