<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class SellFilled extends AbstractDataResource implements SellFilledInterface
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

    public function setNextState(int $id): void
    {
        $record = $this->getHealthyRecord($id);
        $this->table->update(
            [
                'status' => self::STATUS_NEXT,
                'buy_client_order_id' => null,
                'buy_order_id' => null,
                'sell_client_order_id' => null,
                'sell_order_id' => null,
                'note' => null,
                'meta' => null,
            ],
            ['id' => $record->id]
        );
    }
}
