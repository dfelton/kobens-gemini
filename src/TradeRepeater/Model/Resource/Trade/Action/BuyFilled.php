<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Gemini\TradeRepeater\Model\Trade;

final class BuyFilled extends AbstractAction implements BuyFilledInterface
{
    public const STATUS_CURRENT = 'BUY_FILLED';
    public const STATUS_NEXT    = 'SELL_SENT';

    protected function isHealthy(Trade $trade): bool
    {
        return
            $trade->getStatus() === self::STATUS_CURRENT &&
            $trade->isEnabled() === 1 &&
            $trade->isError() === 0 &&
            $trade->getBuyClientOrderId() !== null &&
            $trade->getBuyOrderId() !== null &&
            $trade->getSellClientOrderId() === null &&
            $trade->getSellOrderId() === null &&
            $trade->getMeta();
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
            ['sell_client_order_id' => null, 'status' => self::STATUS_CURRENT],
            ['id' => $id]
        );
    }
}
