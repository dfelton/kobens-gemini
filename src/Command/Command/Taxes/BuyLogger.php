<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Taxes;

use Kobens\Core\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

final class BuyLogger extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'taxes:buy-logger';

    private TableGateway $tblBuyLog;

    private TableGateway $tblTradeHistory;

    private string $symbol;

    protected function configure()
    {
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Trading Symbol', 'btcusd');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->symbol = $input->getOption('symbol');
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
            $this->tblBuyLog = new TableGateway("taxes_{$this->symbol}_buy_log", Db::getAdapter());
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
