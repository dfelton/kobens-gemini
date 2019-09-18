<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class BuyReady extends AbstractDataResource
{
    const STATUS_CURRENT = 'BUY_READY';
    const STATUS_NEXT    = 'BUY_SENT';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->buy_client_order_id === NULL
            && $record->buy_order_id === NULL
            && $record->sell_client_order_id === NULL
            && $record->sell_order_id === NULL;
    }

    public function setNextState(int $id, array $args = []): bool
    {
        if (empty($args['buy_client_order_id'])) {
            throw new \Exception("'buy_client_order_id' is required.");
        }
        $record = $this->getRecord($id);
        if (!$this->isHealthy($record)) {
            throw new \Exception("Order '$id' is not in a healthy state for ".\get_class($this));
        }
        $affectedRows = $this->table->update(
            ['buy_client_order_id' => $args['buy_client_order_id'], 'status' => self::STATUS_NEXT],
            ['id' => $id]
        );
        if ($affectedRows !== 1) {
            throw new \Exception ("Order $id not marked '".self::STATUS_NEXT."'");
        }
        return true;
    }

    public function resetState(int $id): bool
    {
        $affectedRows = $this->table->update(
            ['buy_client_order_id' => null, 'status' => self::STATUS_CURRENT],
            ['id' => $id]
        );
        if ($affectedRows !== 1) {
            throw new \Exception ("Order $id not marked '".self::STATUS_CURRENT."'");
        }
        return true;
    }
}
