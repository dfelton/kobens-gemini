<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Taxes\Pre2021;

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
    protected static $defaultName = 'taxes:pre-2021:buy-logger';

    private ?TableGateway $tblBuyLog = null;

    private ?TableGateway $tblTradeHistory = null;

    private string $symbol;

    protected function configure(): void
    {
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Trading Symbol', 'btcusd');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symbol = $input->getOption('symbol');
        do {
            /** @var \Zend\Db\ResultSet\ResultSetInterface $rows */
            $rows = $this->getTradeHistoryTable()->select(function (Select $select) {
                $select->columns(['tid', 'amount']);
                $select->where->notIn(
                    'tid',
                    (new Select('taxes_' . $this->symbol . '_buy_log'))->columns(['tid'])
                );
                $select->where->equalTo('type', 'buy');
                $select->where->lessThan('trade_date', '2021-01-01 00:00:00');
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
        return 0;
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
            $this->tblTradeHistory = new TableGateway('trade_history_' . $this->symbol, Db::getAdapter());
        }
        return $this->tblTradeHistory;
    }
}
