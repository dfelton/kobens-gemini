<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource;

use Zend\Db\TableGateway\TableGateway;

final class Trade
{
    /**
     * @var TableGateway
     */
    private $table;

    public function __construct(
        \Zend\Db\Adapter\Adapter $adapter
    ) {
        $this->table = new TableGateway('trade_repeater', $adapter);
    }

    public function insert(\Kobens\Gemini\TradeRepeater\Model\Trade $trade): void
    {
        $this->table->insert([
            'is_enabled' => $trade->getIsEnabled(),
            'is_error' => $trade->getIsError(),
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
}
