<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

final class SellFilled extends AbstractAction implements SellFilledInterface
{
    const STATUS_CURRENT = 'SELL_FILLED';
    const STATUS_NEXT    = 'BUY_READY';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->is_enabled === '1'
            && $record->is_error === '0'
            && $record->buy_client_order_id !== null
            && $record->buy_order_id !== null
            && $record->sell_client_order_id !== null
            && $record->sell_order_id !== null;
    }

    public function setNextState(int $id): void
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->getHealthyRecord($id, true);
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
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
    }
}
