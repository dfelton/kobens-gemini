<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class SellSent extends AbstractDataResource implements SellSentInterface
{
    const STATUS_CURRENT = 'SELL_SENT';
    const STATUS_NEXT    = 'SELL_PLACED';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->is_enabled === '1'
            && $record->is_error === '0'
            && $record->buy_client_order_id !== null
            && $record->buy_order_id !== null
            && $record->sell_client_order_id !== null
            && $record->sell_order_id === null;
    }

    public function setNextState(int $id, string $orderId, string $price): void
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->getHealthyRecord($id, true);
            $meta = \json_decode($record['meta'], true);
            $meta['sell_price'] = $price;
            $this->table->update(
                [
                    'sell_order_id' => $orderId,
                    'status' => self::STATUS_NEXT,
                    'meta' => \json_encode($meta),
                ],
                ['id' => $id]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
    }

    public function setErrorState(int $id, string $message): void
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->getRecord($id, true);
            $meta = \json_decode($record->meta, true);
            $meta['error'] = $message;
            $this->table->update(
                [
                    'meta' => \json_encode($meta),
                    'is_error' => 1,
                ],
                ['id' => $id]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
    }
}
