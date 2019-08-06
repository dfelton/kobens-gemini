<?php

namespace Kobens\Gemini\Command\Command\Taxes\Calculator;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\TableGateway\TableGateway;
use Kobens\Core\Db;
use Zend\Db\ResultSet\ResultSet;
use \Zend\Db\Sql\Select;

final class Form8949 extends Command
{
    protected static $defaultName = 'kobens:gemini:taxes:calculator:form8949';

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
        $this->setDescription('Generates data for a US Federal Tax Form 8949.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isComplete = false;
        $this->resetCostBasisTable();
        do {
            $sells = $this->getSellsNotLogged();
            if ($sells->count()) {
                foreach ($sells as $sell) {
                    \Zend\Debug\Debug::dump($sell);
                }
                break;
            } else {
                $isComplete = true;
            }
        } while ($isComplete === false);
    }


    private function resetCostBasisTable(): void
    {
        (new TableGateway('gemini_taxes_cost_bases', Db::getAdapter()))->update([
            'amount_sold' => '0',
            'is_fully_sold' => '0',
        ]);
    }

    private function getSellsNotLogged(): ResultSet
    {
        return $this->getTradeHistoryTable()->select(function (Select $select)
        {
            $subSelect = new Select('gemini_taxes_form8949');
            $subSelect->columns(['transaction_id']);
            $select->where->notIn('transaction_id', $subSelect);
            $select->where->equalTo('side', 'sell');
            $select->order('transaction_id ASC');
            $select->limit(10);
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