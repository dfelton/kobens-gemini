<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action;;

interface ActionInterface
{
    public function getHealthyRecords(): \Generator;

    public function getHealthyRecord(int $id): \ArrayObject;

    public function getRecord(int $id, bool $forUpdate = false): \ArrayObject;
}
