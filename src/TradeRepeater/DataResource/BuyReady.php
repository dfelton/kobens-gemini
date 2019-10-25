<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

final class BuyReady extends AbstractDataResource implements BuyReadyInterface
{
    const STATUS_CURRENT = 'BUY_READY';
    const STATUS_NEXT    = 'BUY_SENT';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->buy_client_order_id === null
            && $record->buy_order_id === null
            && $record->sell_client_order_id === null
            && $record->sell_order_id === null
            && $record->meta === null;
    }

    public function setNextState(int $id, string $buyClientOrderId): void
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->getHealthyRecord($id, true);
            $this->table->update(
                ['buy_client_order_id' => $buyClientOrderId, 'status' => self::STATUS_NEXT],
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
        $this->table->update(
            ['meta' => \json_encode(['error' => $message])],
            ['id' => $id]
        );
    }

    public function resetState(int $id): void
    {
        $this->table->update(
            ['buy_client_order_id' => null, 'status' => self::STATUS_CURRENT],
            ['id' => $id]
        );
    }
}
