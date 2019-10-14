<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class SellFilled extends AbstractDataResource
{
    const STATUS_CURRENT = 'SELL_FILLED';
    const STATUS_NEXT    = 'BUY_READY';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->buy_client_order_id !== null
            && $record->buy_order_id !== null
            && $record->sell_client_order_id !== null
            && $record->sell_order_id !== null;
    }

    public function setNextState(int $id, array $args = []): bool
    {
        if ($args !== []) {
            throw new \Exception("SellFilled StateStepper does not accept any arguments");
        }
        if (!$this->isHealthy($this->getRecord($id))) {
            throw new \Exception("Order '$id' is not in a healthy state for ".\get_class($this));
        }
        $affectedRows = $this->table->update(
            [
                'status' => self::STATUS_NEXT,
                'buy_client_order_id' => null,
                'buy_order_id' => null,
                'sell_client_order_id' => null,
                'sell_order_id' => null,
                'note' => null,
                'meta' => null,
            ],
            ['id' => $id]
        );
        if ($affectedRows !== 1) {
            throw new \Exception ("Order $id not marked '".self::STATUS_NEXT."'");
        }
        return true;
    }
}
