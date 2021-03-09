<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Traits\TradeRepeater;

use Zend\Db\Adapter\Adapter;

trait DbPing
{
    /**
     * TODO: Eliminate this via design pattern eventually. We should be able to just reconnect
     * in most scenario rather than repeatedly pinging the database.
     *
     * @param Adapter $adapter
     */
    private function ping(Adapter $adapter): void
    {
        $adapter->query('SELECT "" AS ping')->execute();
    }
}
