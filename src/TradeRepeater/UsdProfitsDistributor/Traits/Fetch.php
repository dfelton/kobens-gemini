<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\UsdProfitsDistributor\Traits;

use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Zend\Db\Adapter\Adapter;

trait Fetch
{
    private function fetch(TradeResource $resource, Adapter $adapter, int $lastId, string $symbol = null): ?Trade
    {
        if ($symbol) {
            $results = $adapter
                ->query('SELECT `id` FROM `trade_repeater` WHERE `id` > :id AND `status` = :status AND `symbol` = :symbol ORDER BY `id` LIMIT 1')
                ->execute(['id' => $lastId, 'status' => 'BUY_PLACED', 'symbol' => $symbol]);
        } else {
            $results = $adapter
                ->query('SELECT `id` FROM `trade_repeater` WHERE `id` > :id AND `status` = :status ORDER BY `id` LIMIT 1')
                ->execute(['id' => $lastId, 'status' => 'BUY_PLACED']);
        }
        return $results->count() === 1 ? $resource->getById((int) $results->current()['id']) : null;
    }
}
