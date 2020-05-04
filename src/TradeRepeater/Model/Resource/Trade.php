<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource;

use Kobens\Gemini\Exception\TradeRepeater\RecordNotFoundException;
use Kobens\Gemini\TradeRepeater\Model\Trade as TradeModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

final class Trade
{
    private const SELECT_LIMIT = 1000;

    private TableGateway $table;

    private Adapter $adapter;

    public function __construct(
        Adapter $adapter
    ) {
        $this->adapter = $adapter;
        $this->table = new TableGateway('trade_repeater', $adapter);
    }

    public function create(TradeModel $trade): void
    {
        $this->table->insert([
            'is_enabled' => $trade->isEnabled(),
            'is_error' => $trade->isError(),
            'status' => $trade->getStatus(),
            'symbol' => $trade->getSymbol(),
            'buy_client_order_id' => $trade->getBuyClientOrderId(),
            'buy_order_id' => $trade->getBuyOrderId(),
            'buy_amount' => $trade->getBuyAmount(),
            'buy_price' => $trade->getBuyPrice(),
            'sell_client_order_id' => $trade->getSellClientOrderId(),
            'sell_order_id' => $trade->getSellOrderId(),
            'sell_amount' => $trade->getSellAmount(),
            'sell_price' => $trade->getSellPrice(),
            'note' => $trade->getNote(),
            'meta' => $trade->getMeta(),
        ]);
    }

    /**
     * @see TradeModel
     * @yield TradeModel
     */
    public function getActiveByStatus(string $status): \Generator
    {
        $ids = $this->getActiveByStatusIds($status);
        if (!$ids) {
            return;
        }
        $offset = 0;
        do {
            $results = $this->table->select(function(Select $select) use ($ids, $offset)
            {
                $select->where->in('id', $ids);
                $select->limit(self::SELECT_LIMIT);
                $select->offset($offset);
            });
            if ($results->count()) {
                foreach ($results as $data) {
                    yield $this->getTradeModel($data);
                }
            }
            $offset += self::SELECT_LIMIT;
        } while ($results->count());
    }

    private function getActiveByStatusIds(string $status): array
    {
        $sql = 'SELECT id FROM trade_repeater WHERE is_enabled = 1 AND is_error = 0 AND status = :status';
        /** @var \Zend\Db\ResultSet\ResultSet $rows */
        $rows = $this->adapter->query($sql, ['status' => $status]);
        $ids = [];
        if ($rows->count()) {
            foreach ($rows as $row) {
                $ids[] = (int) $row['id'];
            }
        }
        return $ids;
    }

    public function getById(int $id, bool $forUpdate = false): TradeModel
    {
        $sql = 'SELECT * FROM trade_repeater WHERE id = :id';
        if ($forUpdate) {
            $sql .= ' FOR UPDATE';
        }
        /** @var \Zend\Db\ResultSet\ResultSet $rows */
        $rows = $this->adapter->query($sql, ['id' => $id]);
        if ($rows->count() !== 1) {
            throw new RecordNotFoundException(sprintf(
                'Trade record "%d" not found.',
                $id
            ));
        }
        return $this->getTradeModel($rows->current());
    }

    private function getTradeModel(\ArrayObject $data): TradeModel
    {
        return new TradeModel(
            (int) $data['id'],
            (int) $data['is_enabled'],
            (int) $data['is_error'],
            $data['status'],
            $data['symbol'],
            $data['buy_amount'],
            $data['buy_price'],
            $data['sell_amount'],
            $data['sell_price'],
            $data['buy_client_order_id'],
            (int) $data['buy_order_id'] ?: null,
            $data['sell_client_order_id'],
            (int) $data['sell_order_id'] ?: null,
            $data['note'],
            $data['meta'],
            $data['updated_at']
        );
    }
}
