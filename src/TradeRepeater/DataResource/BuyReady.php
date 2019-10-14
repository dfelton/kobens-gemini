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
        $record = $this->getHealthyRecord($id);
        $affectedRows = $this->table->update(
            ['buy_client_order_id' => $buyClientOrderId, 'status' => self::STATUS_NEXT],
            ['id' => $record->id]
        );
        if ($affectedRows !== 1) {
            throw new \Exception("Order $id not marked '".self::STATUS_NEXT."'");
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
        $affectedRows = $this->table->update(
            ['buy_client_order_id' => null, 'status' => self::STATUS_CURRENT],
            ['id' => $id]
        );
        if ($affectedRows !== 1) {
            throw new \Exception("Order $id not marked '".self::STATUS_CURRENT."'");
        }
    }
}
