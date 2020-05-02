<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Gemini\Exception\TradeRepeater\UnhealthyStateException;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Driver\ConnectionInterface;

abstract class AbstractAction
{
    const STATUS_CURRENT = '';

    protected TableGateway $table;

    protected Adapter $adapter;

    protected ConnectionInterface $connection;

    public function __construct(
        Adapter $adapter
    ) {
        $this->adapter = $adapter;
        $this->connection = $adapter->getDriver()->getConnection();
        $this->table = new TableGateway('trade_repeater', $adapter);
    }

    abstract protected function isHealthy(\ArrayObject $record): bool;

    protected function getRecords(): \Zend\Db\ResultSet\ResultSetInterface
    {
        return $this->table->select(function(Select $select) {
            $select->where->equalTo('is_enabled', 1);
            $select->where->equalTo('is_error', 0);
            $select->where->equalTo('status', static::STATUS_CURRENT);
        });
    }

    /**
     * @return \Generator
     */
    public function getHealthyRecords(): \Generator
    {
        foreach ($this->getRecords() as $record) {
            if ($this->isHealthy($record)) {
                yield $record;
            }
        }
    }

    /**
     * @param int $id
     * @throws \Exception
     * @return \ArrayObject
     */
    public function getRecord(int $id, bool $forUpdate = false): \ArrayObject
    {
        $sql = 'SELECT * FROM trade_repeater WHERE id = :id';
        if ($forUpdate) {
            $sql .= ' FOR UPDATE';
        }
        /** @var \Zend\Db\ResultSet\ResultSet $rows */
        $rows = $this->adapter->query($sql, ['id' => $id]);
        if ($rows->count() !== 1) {
            throw new \Exception ("Order ID Not Found");
        }
        return $rows->current();
    }

    /**
     * @param int $id
     * @throws \Exception
     * @return \ArrayObject
     */
    public function getHealthyRecord(int $id, bool $forUpdate = false): \ArrayObject
    {
        $record = $this->getRecord($id, $forUpdate);
        if (!$this->isHealthy($record)) {
            throw new UnhealthyStateException(\sprintf(
                "Trade record '%d' is not healthy for '%s'. Current State: %s",
                $id, static::class, \json_encode($record)
            ));
        }
        return $record;
    }

}
