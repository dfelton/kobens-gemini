<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Gemini\Exception\TradeRepeater\UnhealthyStateException;
use Kobens\Gemini\TradeRepeater\Model\Trade;

final class BuyPlaced extends AbstractAction implements BuyPlacedInterface
{
    public const STATUS_CURRENT = 'BUY_PLACED';
    public const STATUS_NEXT    = 'BUY_FILLED';

    protected function isHealthy(Trade $trade): bool
    {
        return
            $trade->getStatus() === self::STATUS_CURRENT &&
            $trade->isEnabled() === 1 &&
            $trade->isError() === 0 &&
            $trade->getBuyClientOrderId() &&
            $trade->getBuyOrderId() &&
            $trade->getSellClientOrderId() === null &&
            $trade->getSellOrderId() === null;
    }

    public function setNextState(int $id): bool
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->getHealthyRecord($id, true);
            $this->table->update(
                ['status' => self::STATUS_NEXT],
                ['id' => $record->getId()]
            );
            $this->connection->commit();
        } catch (UnhealthyStateException $e) {
            $this->connection->rollback();
            // swallow exception (race between Rest and Websocket FillMonitors)
            return false;
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
        return true;
    }
}
