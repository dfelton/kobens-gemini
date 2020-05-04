<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Gemini\TradeRepeater\Model\Trade;

final class BuyReady extends AbstractAction implements BuyReadyInterface
{
    public const STATUS_CURRENT = 'BUY_READY';
    public const STATUS_NEXT    = 'BUY_SENT';

    protected function isHealthy(Trade $trade): bool
    {
        return
            $trade->getStatus() === self::STATUS_CURRENT &&
            $trade->isEnabled() === 1 &&
            $trade->isError() === 0 &&
            $trade->getBuyClientOrderId() === null &&
            $trade->getBuyOrderId() === null &&
            $trade->getSellClientOrderId() === null &&
            $trade->getSellOrderId() === null &&
            $trade->getMeta() === null;
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
                ['id' => $record->getId()]
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
