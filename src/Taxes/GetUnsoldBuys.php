<?php

namespace Kobens\Gemini\Taxes;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;


/**
 * TODO: Any way we can make this more stateless?
 */
final class GetUnsoldBuys
{
    private const BATCH_SIZE_SELECT = 10000;
    private const BATCH_SIZE_UPDATE = 10000;

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var array[]
     */
    private $tableNames = [
        'btcusd' => [
            'taxes_btcusd_buy_log',
            'trade_history_btcusd',
        ]
    ];

    /**
     * @var array
     */
    private $data = [];

    private $lastTransactionId;

    public function __construct(
        Adapter $adapter,
        string $symbol
    ) {
        if (!\array_key_exists($symbol, $this->tableNames)) {
            throw new \LogicException("Unsupported Symbol '$symbol'");
        }
        $this->table = new TableGateway($this->tableNames[$symbol][0], $adapter);
        $this->adapter = $adapter;
        $this->symbol = $symbol;
    }

    /**
     * @param array $transIdsZero
     * @param int $transId
     * @param string $amount
     */
    public function updateRemaining(array $transIdsZero = [], int $transId = null, string $amount = null): void
    {
        if ($transId !== null) {
            if ($amount === null) {
                throw new \LogicException('Must provide amount remaining for transaction id.');
            }
            $this->adapter->query(
                "UPDATE {$this->tableNames[$this->symbol][0]} SET amount_remaining = :amount WHERE tid = :tid",
                ['amount' => $amount, 'tid' => $transId]
            );
        }
        if ($transIdsZero !== []) {
            // todo: use array_splice to break up into batches if > self::BATCH_SIZE_UPDATE
            $this->adapter->query(
                "UPDATE {$this->tableNames[$this->symbol][0]} SET amount_remaining = '0' WHERE tid IN (:tids)",
                ['tids' => $transIdsZero]
            );
        }
        $this->lastTransactionId = null;
        $this->data = [];
    }

    public function getNextBuyForSale(): ?\ArrayObject
    {
        if ($this->data === []) {
            $this->loadData();
        }
        $next = \array_pop($this->data);
        $this->lastTransactionId = $next['tid'];
        return $next;
    }

    private function loadData(): void
    {
        $sql = "SELECT t1.tid, t2.amount, t2.price, t2.fee_amount, t1.amount_remaining " .
            "FROM {$this->tableNames[$this->symbol][0]} AS t1 " .
            "INNER JOIN {$this->tableNames[$this->symbol][1]} AS t2 ON t1.tid " .
            "WHERE t1.amount_remaining <> '0' "
        ;
        if ($this->lastTransactionId) {
            $sql .= "AND t1.tid > {$this->lastTransactionId} ";
        }
        $sql .= "LIMIT ".self::BATCH_SIZE_SELECT;
        $rows = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        if ($rows->count()) {
            foreach ($rows as $row) {
                $this->data[] = $row;
            }
        }
        // ORDER BY in query would drastically slow down the query.
        $this->data = array_reverse($this->data);
    }
}
