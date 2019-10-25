<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

use Kobens\Gemini\Exception\TradeRepeater\UnhealthyStateException;

final class BuyPlaced extends AbstractDataResource implements BuyPlacedInterface
{
    const STATUS_CURRENT = 'BUY_PLACED';
    const STATUS_NEXT    = 'BUY_FILLED';

    protected function isHealthy(\ArrayObject $record): bool
    {
        return $record->status === self::STATUS_CURRENT
            && $record->buy_client_order_id !== null
            && $record->buy_order_id !== null
            && $record->sell_client_order_id === null
            && $record->sell_order_id === null;
    }

    public function setNextState(int $id): void
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->getHealthyRecord($id, true);
            $this->table->update(
                ['status' => self::STATUS_NEXT],
                ['id' => $record->id]
            );
            $this->connection->commit();
        } catch (UnhealthyStateException $e) {
            $this->connection->rollback();
            // silently eat the exception (race between Rest and Websocket FillMonitors)
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
    }
}
