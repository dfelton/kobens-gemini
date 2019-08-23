<?php

namespace Kobens\Gemini\Command\Command\Taxes\Calculator;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\TableGateway\TableGateway;
use Kobens\Core\Db;
use Zend\Db\ResultSet\ResultSet;
use \Zend\Db\Sql\Select;

final class CostBasisLogger extends Command
{
    protected static $defaultName = 'kobens:gemini:taxes:calculator:cost-basis';

    /**
     * @var TableGateway
     */
    private $tblCostBasis;

    /**
     * @var TableGateway
     */
    private $tblTradeHistory;

    protected function configure()
    {
        $this->setDescription('Maintains cache of private order book data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isComplete = false;
        do {
            $buys = $this->getBuysLackingCostBasis();
            if ($buys->count()) {
                foreach ($buys as $buy) {
                    if ($buy->symbol !== 'btcusd') {
                        throw new \Exception ('Only BTCUSD Calculations supported at this time');
                    }

                    $basePrice = \bcmul($buy->price, $buy->amount, 8+2); // btcPrecision + usdPrecision
                    $costBasis = \bcadd($basePrice, $buy->fee_amount, 8+2+5);   // btcPrecision + usdPrecision + feePrecision
                    $costBasis = \rtrim($costBasis, '0');

                    $subunits = \explode('.', $buy->amount);
                    if (!isset($subunits[1])) {
                        $subunits[1] = '0';
                    }
                    $subunits[1] = \str_pad($subunits[1], 8, '0', STR_PAD_RIGHT);
                    $subunits = $subunits[0] . $subunits[1];
                    $subunits = \ltrim($subunits, '0');

                    // TODO: How do we know what the required precision is here?
                    // Answer: By not performing division. multiple the price of the whole unit by 0.00000001
                    $costBasisPerSubunit = \bcdiv($costBasis, $subunits, 100);
                    $costBasisPerSubunit = \rtrim($costBasisPerSubunit, '0');

                    $this->getCostBasisTable()->insert([
                        'transaction_id' => $buy->transaction_id,
                        'cost_basis_per_subunit' => $costBasisPerSubunit
                    ]);
                }
            } else {
                $isComplete = true;
            }

        } while ($isComplete === false);

    }

    private function getBuysLackingCostBasis(): ResultSet
    {
        return $this->getTradeHistoryTable()->select(function (Select $select)
        {
            $subSelect = new Select('gemini_taxes_cost_basis');
            $subSelect->columns(['transaction_id']);
            $select->where->notIn('transaction_id', $subSelect);
            $select->where->equalTo('side', 'buy');
            $select->limit(500);
        });
    }

    private function getCostBasisTable(): TableGateway
    {
        if (!$this->tblCostBasis) {
            $this->tblCostBasis = new TableGateway('gemini_taxes_cost_basis', Db::getAdapter());
        }
        return $this->tblCostBasis;
    }

    private function getTradeHistoryTable(): TableGateway
    {
        if (!$this->tblTradeHistory) {
            $this->tblTradeHistory = new TableGateway('gemini_trade_history', Db::getAdapter());
        }
        return $this->tblTradeHistory;
    }
}