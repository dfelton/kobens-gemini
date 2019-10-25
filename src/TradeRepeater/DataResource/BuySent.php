<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class BuySent extends AbstractDataResource implements BuySentInterface
{
    const STATUS_CURRENT = 'BUY_SENT';
    const STATUS_NEXT    = 'BUY_PLACED';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->is_active === '1'
            && $record->is_error === '0'
            && \is_string($record->buy_client_order_id)
            && \strlen($record->buy_client_order_id) > 0
            && $record->buy_order_id === null
            && $record->sell_client_order_id === null
            && $record->sell_order_id === null
            && $record->meta === null;
    }

    public function setNextState(int $id, string $buyOrderId, string $buyPrice): void
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->getHealthyRecord($id, true);
            $this->table->update(
                [
                    'buy_order_id' => $buyOrderId,
                    'status' => self::STATUS_NEXT,
                    'meta' => \json_encode(['buy_price' => $buyPrice])
                ],
                ['id' => $record->id]
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
            $meta = $record->meta === null ? [] : \json_decode($record->meta, true);
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
