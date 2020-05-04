<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Gemini\TradeRepeater\Model\Trade;

final class SellSent extends AbstractAction implements SellSentInterface
{
    public const STATUS_CURRENT = 'SELL_SENT';
    public const STATUS_NEXT    = 'SELL_PLACED';

    protected function isHealthy(Trade $trade): bool
    {
        return
            $trade->getStatus() === self::STATUS_CURRENT &&
            $trade->isEnabled() === 1 &&
            $trade->isError() === 0 &&
            $trade->getBuyClientOrderId() &&
            $trade->getBuyOrderId() &&
            $trade->getSellClientOrderId() &&
            $trade->getSellOrderId() === null;
    }

    public function setNextState(int $id, string $orderId, string $price): void
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->getHealthyRecord($id, true);
            $meta = \json_decode($record->getMeta(), true);
            $meta['sell_price'] = $price;
            $this->table->update(
                [
                    'sell_order_id' => $orderId,
                    'status' => self::STATUS_NEXT,
                    'meta' => \json_encode($meta),
                ],
                ['id' => $id]
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
            $meta = \json_decode($record->getMeta(), true);
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
