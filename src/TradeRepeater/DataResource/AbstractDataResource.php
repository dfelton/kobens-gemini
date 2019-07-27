<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

use Kobens\Core\Db;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Kobens\Gemini\TradeRepeater\StateStepperInterface;

abstract class AbstractDataResource implements StateStepperInterface
{
    const STATUS_CURRENT = '';

    /**
     * @var TableGateway
     */
    protected $table;

    public function __construct()
    {
        $this->table = new TableGateway('gemini_trade_repeater', Db::getAdapter());
    }

    abstract protected function isHealthy(\ArrayObject $record) : bool;

    protected function getRecords(): \Zend\Db\ResultSet\ResultSetInterface
    {
        return $this->table->select(function(Select $select) {
            $select->where->equalTo('is_enabled', 1);
            $select->where->equalTo('status', static::STATUS_CURRENT);
        });
    }

    public function getHealthyRecords() : \Generator
    {
        foreach ($this->getRecords() as $record) {
            if ($this->isHealthy($record)) {
                yield $record;
            }
        }
    }

    public function getUnhealthyRecords() : \Generator
    {
        foreach ($this->getRecords() as $record) {
            if (!$this->isHealthy($record)) {
                yield $record;
            }
        }
    }

    public function setNote(int $id, string $note): bool
    {
        if (strlen($note) > 255) {
            throw new \Exception ('Note Max Length is 255');
        }
        $affectedRows = $this->table->update(['note' => $note], ['id' => $id]);
        return $affectedRows === 1;
    }

    /**
     * @param int $id
     * @throws \Exception
     * @return \ArrayObject
     */
    public function getRecord(int $id) : \ArrayObject
    {
        $rows = $this->table->select(function(Select $select) use ($id) {
            $select->where->equalTo('id', $id);
        });
        if ($rows->count() !== 1) {
            throw new \Exception ("Order ID Not Found");
        }
        foreach ($rows as $row) {
            return $row;
        }
    }

}
