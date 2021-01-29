<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade;

use Kobens\Gemini\TradeRepeater\Model\Trade;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

final class Update implements UpdateInterface
{
    private TableGateway $table;

    private Adapter $adapter;

    public function __construct(
        Adapter $adapter
    ) {
        $this->adapter = $adapter;
        $this->table = new TableGateway('trade_repeater', $adapter);
    }

    public function execute(Trade $trade): void
    {
        $this->table->update(
            [
                'buy_amount' => $trade->getBuyAmount(),
                'buy_client_order_id' => $trade->getBuyClientOrderId(),
                'buy_order_id' => $trade->getBuyOrderId(),
                'buy_price' => $trade->getBuyPrice(),
                'meta' => $trade->getMeta(),
                'note' => $trade->getNote(),
                'sell_amount' => $trade->getSellAmount(),
                'sell_client_order_id' => $trade->getSellClientOrderId(),
                'sell_order_id' => $trade->getSellOrderId(),
                'sell_price' => $trade->getSellPrice(),
                'status' => $trade->getStatus(),
            ],
            ['id' => $trade->getId()]
        );
    }

    public function updateAmounts(int $id, string $buyAmount, string $sellAmmount): void
    {
        $this->table->update(
            [
                'buy_amount' => $buyAmount,
                'sell_amount' => $sellAmmount,
            ],
            ['id' => $id]
        );
    }

}
