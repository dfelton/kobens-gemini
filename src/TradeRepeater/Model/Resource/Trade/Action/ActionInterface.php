<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;

use Kobens\Gemini\TradeRepeater\Model\Trade;

interface ActionInterface
{
    public function getHealthyRecords(): \Generator;

    public function getHealthyRecord(int $id): Trade;

    public function getRecord(int $id, bool $forUpdate = false): Trade;
}
