<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Gemini\TradeRepeater\Model\Trade;

final class SellFilled extends AbstractAction implements SellFilledInterface
{
    public const STATUS_CURRENT = 'SELL_FILLED';
    public const STATUS_NEXT    = 'BUY_READY';

    protected function isHealthy(Trade $trade): bool
    {
        return
            $trade->getStatus() === self::STATUS_CURRENT &&
            $trade->isEnabled() === 1 &&
            $trade->isError() === 0 &&
            $trade->getBuyClientOrderId() &&
            $trade->getBuyOrderId() &&
            $trade->getSellClientOrderId() &&
            $trade->getSellOrderId();
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
                ['id' => $record->getId()]
            );
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
    }
}
