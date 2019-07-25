<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class SellSent extends AbstractDataResource
{
    const STATUS_CURRENT = 'SELL_SENT';
    const STATUS_NEXT    = 'SELL_PLACED';

    protected function isHealthy(\ArrayObject $record) : bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->buy_client_order_id !== NULL
            && $record->buy_order_id !== NULL
            && $record->sell_client_order_id !== NULL
            && $record->sell_order_id === NULL;
    }

    public function setNextState(int $id, array $args = []) : bool
    {
        if (empty($args['sell_order_id'])) {
            throw new \Exception("'sell_order_id' is required.");
        }
        $record = $this->getRecord($id);
        if (!$this->isHealthy($record)) {
            throw new \Exception("Order '$id' is not in a healthy state for ".\get_class($this));
        }
        $affectedRows = $this->table->update(
            ['sell_order_id' => $args['sell_order_id'], 'status' => self::STATUS_NEXT],
            ['id' => $id]
        );
        if ($affectedRows !== 1) {
            throw new \Exception ("Order $id not marked '".self::STATUS_NEXT."'");
        }
        return true;
    }
}
