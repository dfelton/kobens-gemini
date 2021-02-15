<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource;

use Kobens\Gemini\Exception\TradeRepeater\RecordNotFoundException;
use Kobens\Gemini\TradeRepeater\Model\Trade as TradeModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Kobens\Math\BasicCalculator\Compare;

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
            $results = $this->table->select(function (Select $select) use ($ids, $offset) {
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

    /**
     * Supported $filters keys:
     *  - status (string): status must be equal to provided value
     *  - status (array): status must be equal to a value in provided array
     *  - buy_price (string): buy price must be equal to provided value
     *  - buy_price (array): buy_price must be equal to a value in provided array
     *  - buy_price_gte (string): buy_price must be greater than or equal to provided value
     *  - buy_price_gt (string): buy_price must be greater than provided value
     *  - buy_price_lte (string): buy_price must be less than or equal to provided value
     *  - buy_price_lt (string): buy_price must be less than provided value
     *  - sell_price (string): sell price must be qual to provid value
     *  - sell_price (array): buy_price must be equal to a value in provided array
     *  - sell_price_gte (string): sell_price must be greater than or equal to provided value
     *  - sell_price_gt (string): sell_price must be greater than provided value
     *  - sell_price_lte (string): sell_price must be less than or equal to provided value
     *  - sell_price_lt (string): sell_price must be less than provided value
     *
     * @param string $symbol
     * @param array $filters
     * @return \Generator
     */
    public function getList(string $symbol = null, array $filters = []): \Generator
    {
        $lastId = 0;
        do {
            $stmt = $this->adapter->query(
                'SELECT `id` FROM `trade_repeater` WHERE `symbol` = :symbol AND `id` > :id ORDER BY `id` ASC LIMIT 500'
            );
            $rows = $stmt->execute(['symbol' => $symbol, 'id' => $lastId]);
            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    // We intentionally fetch individually inside this loop, so that if the invoker is
                    // performing an operation that takes time, we filter by the state of the record at
                    // at the time we're iterating over it not when we first queried for records.
                    $model = $this->getById((int) $row['id']);
                    if ($this->isSelected($model, $filters) === true) {
                        yield $model;
                    }
                    $lastId = $row['id'];
                }
            }
        } while ($rows->count() > 0);
    }

    private function isSelected(TradeModel $trade, array $filters): bool
    {
        if ($filters === []) {
            return true;
        }
        if (($filters['status'] ?? null) !== null) {
            if (is_string($filters['status'])) {
                $status = [$filters['status']];
            } elseif (is_array($filters['status'])) {
                $status = $filters['status'];
            }
            if (!in_array($trade->getStatus(), $status)) {
                return false;
            }
        }
        if (($filters['buy_price'] ?? null) !== null) {
            if (is_string($filters['buy_price'])) {
                $buyPrices = [$filters['buy_price']];
            } elseif (is_array($filters['buy_price'])) {
                $buyPrices = $filters['buy_price'];
            }
            foreach ($buyPrices as $buyPrice) {
                if (Compare::getResult($trade->getBuyPrice(), $buyPrice) !== Compare::EQUAL) {
                    return false;
                }
            }
        }
        if (($filters['sell_price'] ?? null) !== null) {
            if (is_string($filters['sell_price'])) {
                $sellPrices = [$filters['sell_price']];
            } elseif (is_array($filters['sell_price'])) {
                $sellPrices = $filters['sell_price'];
            }
            foreach ($sellPrices as $sellPrice) {
                if (Compare::getResult($trade->getSellPrice(), $sellPrice) !== Compare::EQUAL) {
                    return false;
                }
            }
        }
        if (($filters['buy_price_lte'] ?? null) !== null) {
            if (Compare::getResult($trade->getBuyPrice(), $filters['buy_price_lte']) === Compare::LEFT_GREATER_THAN) {
                return false;
            }
        }
        if (($filters['buy_price_lt'] ?? null) !== null) {
            if (in_array(Compare::getResult($trade->getBuyPrice(), $filters['buy_price_lt']), [Compare::LEFT_GREATER_THAN, Compare::EQUAL])) {
                return false;
            }
        }
        if (($filters['buy_price_gte'] ?? null) !== null) {
            if (Compare::getResult($trade->getBuyPrice(), $filters['buy_price_gte']) === Compare::LEFT_LESS_THAN) {
                return false;
            }
        }
        if (($filters['buy_price_gt'] ?? null) !== null) {
            if (in_array(Compare::getResult($trade->getBuyPrice(), $filters['buy_price_gt']), [Compare::LEFT_LESS_THAN, Compare::EQUAL])) {
                return false;
            }
        }
        if (($filters['sell_price_lte'] ?? null) !== null) {
            if (Compare::getResult($trade->getSellPrice(), $filters['sell_price_lte']) === Compare::LEFT_GREATER_THAN) {
                return false;
            }
        }
        if (($filters['sell_price_lt'] ?? null) !== null) {
            if (in_array(Compare::getResult($trade->getSellPrice(), $filters['sell_price_lt']), [Compare::LEFT_GREATER_THAN, Compare::EQUAL])) {
                return false;
            }
        }
        if (($filters['sell_price_gte'] ?? null) !== null) {
            if (Compare::getResult($trade->getSellPrice(), $filters['sell_price_gte']) === Compare::LEFT_LESS_THAN) {
                return false;
            }
        }
        if (($filters['sell_price_gt'] ?? null) !== null) {
            if (in_array(Compare::getResult($trade->getSellPrice(), $filters['sell_price_gt']), [Compare::LEFT_LESS_THAN, Compare::EQUAL])) {
                return false;
            }
        }
        return true;
    }
}
