<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Stats\SevenDayAggregate;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;

final class Updater
{
    private Adapter $adapter;

    private TableGatewayInterface $tblArchive;

    private TableGatewayInterface $tblStats;

    public function __construct(
        Adapter $adapter
    ) {
        $this->adapter = $adapter;
        $this->tblArchive = new TableGateway('trade_repeater_archive', $adapter);
        $this->tblStats = new TableGateway('repeater_stats_7day_aggregate', $adapter);
    }

    public function execute(): void
    {
        $data = $this->getData();
        $this->adapter->driver->getConnection()->execute('TRUNCATE repeater_stats_7day_aggregate');
        foreach ($data as $row) {
            $this->tblStats->insert($row);
        }
    }

    private function getData(): array
    {
        $data = $this->tblArchive->select(function (Select $select): void {
            $select->columns(['buy_client_order_id', new Expression('count(*)')]);
            $select->where(function (Where $where): void {
                $where->greaterThanOrEqualTo('created_at', date('Y-m-d H:i:s', time() - 604800));
            });
            $select->where('repeater_id IN (SELECT id from trade_repeater)');
            $select->group('repeater_id');
        });
        $raw = [];
        foreach ($data as $row) {
            $id = (int) (explode('_', $row->buy_client_order_id)[1]);
            $raw[] = ['repeater_id' => $id, 'count' => (int) $row->Expression1];
        }
        return $raw;
    }
}
