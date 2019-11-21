<?php

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

final class BuyFilled extends AbstractAction implements BuyFilledInterface
{
    const STATUS_CURRENT = 'BUY_FILLED';
    const STATUS_NEXT    = 'SELL_SENT';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->is_enabled === '1'
            && $record->is_error === '0'
            && $record->buy_client_order_id !== null
            && $record->buy_order_id !== null
            && $record->sell_client_order_id === null
            && $record->sell_order_id === null
            && $record->meta !== null;
    }

    public function setNextState(int $id, string $sellClientOrderId): void
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->getHealthyRecord($id, true);
            $this->table->update(
                [
                    'sell_client_order_id' => $sellClientOrderId,
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
            ['sell_client_order_id' => null, 'status' => self::STATUS_CURRENT],
            ['id' => $id]
        );
    }
}
