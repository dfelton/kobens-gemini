<?php

declare(strict_types=1);

namespace Kobens\Gemini\Report\YearToDateVolume\DataProvider;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class Zend
{
    private const BATCH_SIZE = 10000;

    private Adapter $adapter;

    public function __construct(
        Adapter $adapter
        ) {
            $this->adapter = $adapter;
    }

    public function getTrades(string $symbol, int $year): \Generator
    {
        $table = $this->getTable($symbol);
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

    private function getNextBatch(TableGateway $table, int $lastTransactionId, int $year): ResultSetInterface
    {
        return $table->select(function (Select $select) use ($lastTransactionId, $year): void {
            $select->where->greaterThan('tid', $lastTransactionId);
            $select->where->lessThan('trade_date', ($year + 1) . '-01-01 00:00:00');
            //             $select->where->equalTo('type', 'sell');
            $select->order('tid ASC');
            $select->limit(self::BATCH_SIZE);
        });
    }

    private function getTable(string $symbol): TableGateway
    {
        return new TableGateway('trade_history_' . $symbol, $this->adapter);
    }
}