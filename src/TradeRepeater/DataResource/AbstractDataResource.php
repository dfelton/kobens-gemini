<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

use Kobens\Core\Db;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

abstract class AbstractDataResource
{
    const STATUS_CURRENT = '';

    /**
     * @var TableGateway
     */
    protected $table;

    public function __construct()
    {
        $this->table = new TableGateway('trade_repeater', Db::getAdapter());
    }

    abstract protected function isHealthy(\ArrayObject $record): bool;

    protected function getRecords(): \Zend\Db\ResultSet\ResultSetInterface
    {
        return $this->table->select(function(Select $select) {
            $select->where->equalTo('is_enabled', 1);
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
    public function getRecord(int $id): \ArrayObject
    {
        /** @var \Zend\Db\ResultSet\ResultSet $rows */
        $rows = $this->table->select(function(Select $select) use ($id) {
            $select->where->equalTo('id', $id);
        });
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
    public function getHealthyRecord(int $id): \ArrayObject
    {
        $record = $this->getRecord($id);
        if (!$this->isHealthy($record)) {
            throw new \Exception(\sprintf(
                "Trade record '%d' is not healthy for '%s'",
                $id,
                self::class
            ));
        }
        return $record;
    }

}
