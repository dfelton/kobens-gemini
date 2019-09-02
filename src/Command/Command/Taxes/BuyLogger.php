<?php

namespace Kobens\Gemini\Command\Command\Taxes;

use Kobens\Core\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

final class BuyLogger extends Command
{

    /**
     * @var string
     */
    protected static $defaultName = 'taxes:buy-logger';

    /**
     * @var TableGateway
     */
    private $tblBuyLog;

    /**
     * @var TableGateway
     */
    private $tblTradeHistory;

    /**
     * @var string
     */
    private $symbol;

    protected function configure()
    {
        $this->addArgument('symbol', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->symbol = $input->getArgument('symbol');
        do {
            /** @var \Zend\Db\ResultSet\ResultSetInterface $rows */
            $rows = $this->getTradeHistoryTable()->select(function (Select $select)
            {
                $select->columns(['tid','amount']);
                $select->where->notIn('tid', (new Select('taxes_'.$this->symbol.'_buy_log'))->columns(['tid']));
                $select->where->equalTo('type', 'buy');
                $select->order('tid ASC');
                $select->limit(10000);
            });
            foreach ($rows as $row) {
                $this->getBuyLogTable()->insert([
                    'tid' => $row['tid'],
                    'amount_remaining' => $row['amount'],
                ]);
            }
        } while ($rows->count() > 0);
    }

    private function getBuyLogTable(): TableGateway
    {
        if (!$this->tblBuyLog) {
            $this->tblBuyLog = new TableGateway('taxes_btcusd_buy_log', Db::getAdapter());
        }
        return $this->tblBuyLog;
    }

    private function getTradeHistoryTable(): TableGateway
    {
        if (!$this->tblTradeHistory) {
            $this->tblTradeHistory = new TableGateway('trade_history_'.$this->symbol, Db::getAdapter());
        }
        return $this->tblTradeHistory;
    }

}
