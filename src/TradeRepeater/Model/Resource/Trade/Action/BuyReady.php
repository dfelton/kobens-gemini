<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

final class BuyReady extends AbstractAction implements BuyReadyInterface
{
    const STATUS_CURRENT = 'BUY_READY';
    const STATUS_NEXT    = 'BUY_SENT';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->is_enabled === '1'
            && $record->is_error === '0'
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
                [
                    'buy_client_order_id' => $buyClientOrderId,
                    'status' => self::STATUS_NEXT,
                ],
                ['id' => $record->id]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
    }

    public function resetState(int $id): void
    {
        $this->table->update(
            ['buy_client_order_id' => null, 'status' => self::STATUS_CURRENT],
            ['id' => $id]
        );
    }
}
