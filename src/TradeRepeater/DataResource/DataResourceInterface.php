<?php

namespace Kobens\Gemini\TradeRepeater\DataResource;

interface DataResourceInterface
{
    public function getHealthyRecords(): \Generator;

    public function getHealthyRecord(int $id): \ArrayObject;

    public function getRecord(int $id, bool $forUpdate = false): \ArrayObject;
}
