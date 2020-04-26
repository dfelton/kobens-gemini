<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\WebSocket\OrderEvents;

interface BookKeeperInterface
{
    public function openBook(): void;
}
