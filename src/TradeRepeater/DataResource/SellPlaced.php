<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class SellPlaced extends AbstractDataResource implements SellPlacedInterface
{
    const STATUS_CURRENT = 'SELL_PLACED';
    const STATUS_NEXT    = 'SELL_FILLED';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->buy_client_order_id !== null
            && $record->buy_order_id !== null
            && $record->sell_client_order_id !== null
            && $record->sell_order_id !== null;
    }

    public function setNextState(int $id): void
    {
        $record = $this->getHealthyRecord($id);
        $this->table->update(
            ['status' => self::STATUS_NEXT],
            ['id' => $record->id]
        );
    }
}
