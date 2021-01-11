<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model;

interface GetRecordIdInterface
{
    /**
     * @param string $clientOrderId
     * @throws \InvalidArgumentException
     * @return int
     */
    public function get(string $clientOrderId): int;
}
