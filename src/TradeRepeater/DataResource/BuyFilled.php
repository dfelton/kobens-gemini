<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class BuyFilled extends AbstractDataResource implements BuyFilledInterface
{
    const STATUS_CURRENT = 'BUY_FILLED';
    const STATUS_NEXT    = 'SELL_SENT';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->buy_client_order_id !== null
            && $record->buy_order_id !== null
            && $record->sell_client_order_id === null
            && $record->sell_order_id === null
            && $record->meta !== null;
    }

    public function setNextState(int $id, string $sellClientOrderId): void
    {
        $record = $this->getHealthyRecord($id);
        $this->table->update(
            ['sell_client_order_id' => $sellClientOrderId, 'status' => self::STATUS_NEXT],
            ['id' => $record->id]
        );
    }

    public function setErrorState(int $id, string $message): void
    {
        $record = $this->getRecord($id);
        $meta = \json_decode($record->meta, true);
        $meta['error'] = $message;
        $this->table->update(
            ['meta' => \json_encode($meta)],
            ['id' => $record->id]
        );
    }

    public function resetState(int $id): void
    {
        $this->table->update(
            ['sell_client_order_id' => null, 'status' => self::STATUS_CURRENT],
            ['id' => $id]
        );
    }
}
