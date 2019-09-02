<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

use Kobens\Core\Db;
use Zend\Db\TableGateway\TableGateway;

final class Archive
{
    /**
     * @var TableGateway
     */
    private $table;

    private $args = [
        'symbol',
        'buy_client_order_id',
        'buy_order_id',
        'buy_amount',
        'buy_price',
        'sell_client_order_id',
        'sell_order_id',
        'sell_amount',
        'sell_price',
        'meta'
    ];

    public function __construct()
    {
        $this->table = new TableGateway('trade_repeater_archive', Db::getAdapter());
    }

    public function addArchive(array $args): void
    {
        $data = [];
        foreach ($this->args as $arg) {
            if (empty($args[$arg])) {
                throw new \Exception("Missing required '%arg' argument.");
            }
            $data[$arg] = $args[$arg];
        };
        $this->table->insert($data);
    }

}
