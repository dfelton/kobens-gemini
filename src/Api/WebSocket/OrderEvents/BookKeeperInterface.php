<?php

namespace Kobens\Gemini\Api\WebSocket\OrderEvents;

interface BookKeeperInterface
{
    public function openBook(): void;
}
