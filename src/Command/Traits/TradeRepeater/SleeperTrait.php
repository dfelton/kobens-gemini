<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Traits\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;

trait SleeperTrait
{
    public function sleep(int $sleepTime, SleeperInterface $sleeper, EmergencyShutdownInterface $shutdown): void
    {
        $sleeper->sleep(
            $sleepTime,
            function () use ($shutdown) {
                return $shutdown->isShutdownModeEnabled();
            }
        );
    }
}
