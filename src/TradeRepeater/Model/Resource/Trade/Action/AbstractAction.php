<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Gemini\Exception\TradeRepeater\UnhealthyStateException;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ConnectionInterface;
use Zend\Db\TableGateway\TableGateway;

abstract class AbstractAction
{
    protected const STATUS_CURRENT = '';

    protected TableGateway $table;

    protected Adapter $adapter;

    protected ConnectionInterface $connection;

    protected TradeResource $tradeResource;

    public function __construct(
        Adapter $adapter,
        TradeResource $tradeResource
    ) {
        $this->adapter = $adapter;
        $this->connection = $adapter->getDriver()->getConnection();
        $this->table = new TableGateway('trade_repeater', $adapter);
        $this->tradeResource = $tradeResource;
    }

    abstract protected function isHealthy(Trade $record): bool;

    public function getHealthyRecords(): \Generator
    {
        $trades = $this->tradeResource->getActiveByStatus(static::STATUS_CURRENT);
        foreach ($trades as $trade) {
            yield $trade;
        }
    }

    public function getRecord(int $id, bool $forUpdate = false): Trade
    {
        return $this->tradeResource->getById($id, $forUpdate);
    }

    /**
     * @param int $id
     * @throws UnhealthyStateException
     * @return Trade
     */
    public function getHealthyRecord(int $id, bool $forUpdate = false): Trade
    {
        $record = $this->getRecord($id, $forUpdate);
        if (!$this->isHealthy($record)) {
            throw new UnhealthyStateException(\sprintf(
                "Trade record '%d' is not healthy for '%s'. Current State: %s",
                $id,
                static::class,
                \json_encode($record)
            ));
        }
        return $record;
    }
}
