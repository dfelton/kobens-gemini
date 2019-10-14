<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class BuySent extends AbstractDataResource implements BuySentInterface
{
    const STATUS_CURRENT = 'BUY_SENT';
    const STATUS_NEXT    = 'BUY_PLACED';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && \is_string($record->buy_client_order_id)
            && \strlen($record->buy_client_order_id) > 0
            && $record->buy_order_id === NULL
            && $record->sell_client_order_id === NULL
            && $record->sell_order_id === NULL;
    }

    public function setNextState(int $id, string $buyOrderId, string $buyPrice): void
    {
        $record = $this->getHealthyRecord($id);
        $affectedRows = $this->table->update(
            [
                'buy_order_id' => $buyOrderId,
                'status' => self::STATUS_NEXT,
                'meta' => \json_encode(['buy_price' => $buyPrice])
            ],
            ['id' => $record->id]
        );
        if ($affectedRows !== 1) {
            throw new \Exception("Order '$id' not marked '".self::STATUS_NEXT."'");
        }
    }

    public function setErrorState(int $id, string $message): void
    {
        $record = $this->getRecord($id);
        $meta = $record->meta === null ? [] : \json_decode($record->meta, true);
        $meta['error'] = $message;
        $this->table->update(['meta' => \json_encode($meta)], ['id' => $id]);
    }
}
