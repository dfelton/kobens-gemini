<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class SellPlaced extends AbstractDataResource
{
    const STATUS_CURRENT = 'SELL_PLACED';
    const STATUS_NEXT    = 'SELL_FILLED';

    protected function isHealthy(\ArrayObject $record) : bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->buy_client_order_id !== NULL
            && $record->buy_order_id !== NULL
            && $record->sell_client_order_id !== NULL
            && $record->sell_order_id !== NULL;
    }

    public function setNextState(int $id, array $args = []) : bool
    {
        if ($args !== []) {
            throw new \Exception("SellPlaced StateStepper does not accept any arguments");
        }
        $record = $this->getRecord($id);
        if (!$this->isHealthy($record)) {
            throw new \Exception("Order '$id' is not in a healthy state for ".\get_class($this));
        }
        $affectedRows = $this->table->update(
            ['status' => self::STATUS_NEXT],
            ['id' => $id]
        );
        if ($affectedRows !== 1) {
            throw new \Exception ("Order $id not marked '".self::STATUS_NEXT."'");
        }
        return true;
    }
}
