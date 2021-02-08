<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Core\Db;
use Zend\Db\TableGateway\TableGateway;
use Kobens\Gemini\TradeRepeater\Model\Trade;

final class Archive implements ArchiveInterface
{
    private TableGateway $table;

    public function __construct()
    {
        $this->table = new TableGateway('trade_repeater_archive', Db::getAdapter());
    }

    public function addArchive(
        Trade $trade
    ): void {
        $meta = \json_decode($trade->getMeta());
        $this->table->insert([
            'repeater_id'          => $trade->getId(),
            'symbol'               => $trade->getSymbol(),
            'buy_client_order_id'  => $trade->getBuyClientOrderId(),
            'buy_order_id'         => $trade->getBuyOrderId(),
            'buy_amount'           => $trade->getBuyAmount(),
            'buy_price'            => (string) $meta->buy_price,
            'sell_client_order_id' => $trade->getSellClientOrderId(),
            'sell_order_id'        => $trade->getSellOrderId(),
            'sell_amount'          => $trade->getSellAmount(),
            'sell_price'           => (string) $meta->sell_price
        ]);
    }
}
