<?php

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Core\Db;
use Zend\Db\TableGateway\TableGateway;

final class Archive implements ArchiveInterface
{
    /**
     * @var TableGateway
     */
    private $table;

    public function __construct()
    {
        $this->table = new TableGateway('trade_repeater_archive', Db::getAdapter());
    }

    public function addArchive(
        string $symbol,
        string $buyClientOrderId,
        string $buyOrderId,
        string $buyAmount,
        string $buyPrice,
        string $sellClientOrderId,
        string $sellOrderId,
        string $sellAmount,
        string $sellPrice
    ): void
    {
        $this->table->insert([
            'symbol'               => $symbol,
            'buy_client_order_id'  => $buyClientOrderId,
            'buy_order_id'         => $buyOrderId,
            'buy_amount'           => $buyAmount,
            'buy_price'            => $buyPrice,
            'sell_client_order_id' => $sellClientOrderId,
            'sell_order_id'        => $sellOrderId,
            'sell_amount'          => $sellAmount,
            'sell_price'           => $sellPrice,
        ]);
    }
}