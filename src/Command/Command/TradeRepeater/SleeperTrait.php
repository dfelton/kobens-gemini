<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;

/**
 * TODO From an organizational aspect this doesn't really belong in this namespace.
 */
trait SleeperTrait
{
    public function sleep(int $sleepTime, SleeperInterface $sleeper, EmergencyShutdownInterface $shutdown): void
    {
        $sleeper->sleep(
            $sleepTime,
            function() use ($shutdown)
            {
                return $shutdown->isShutdownModeEnabled();
            }
        );
    }
}
