<?php

declare(strict_types=1);

namespace Kobens\Gemini\Report\YearToDateVolume;

interface DataProviderInterface
{
    /**
     * @return \Kobens\Gemini\ApiModels\TradeInterface[]
     */
    public function getTrades(string $symbol, int $year): \Generator;
}
