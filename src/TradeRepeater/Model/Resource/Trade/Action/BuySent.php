<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Gemini\TradeRepeater\Model\Trade;

final class BuySent extends AbstractAction implements BuySentInterface
{
    public const STATUS_CURRENT = 'BUY_SENT';
    public const STATUS_NEXT    = 'BUY_PLACED';

    protected function isHealthy(Trade $trade): bool
    {
        return
            $trade->getStatus() === self::STATUS_CURRENT &&
            $trade->isEnabled() === 1 &&
            $trade->isError() === 0 &&
            $trade->getBuyClientOrderId() &&
            $trade->getBuyOrderId() === null &&
            $trade->getSellClientOrderId() === null &&
            $trade->getSellOrderId() === null &&
            $trade->getMeta() === null;
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
                ['id' => $record->getId()]
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
            $meta = $record->getMeta() === null ? [] : \json_decode($record->getMeta(), true);
            $meta['error'] = $message;
            $this->table->update(
                [
                    'meta' => \json_encode($meta),
                    'is_error' => 1,
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
