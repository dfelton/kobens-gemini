<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Traits\TradeRepeater;

use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Gemini\Command\Traits\GetNow;
use Kobens\Gemini\Command\Traits\KillFile;

trait ExitProgram
{
    use KillFile;
    use GetNow;

    private function outputExit(OutputInterface $output, EmergencyShutdownInterface $shutdown, string $killFile): void
    {
        if ($shutdown->isShutdownModeEnabled()) {
            $output->writeln(sprintf(
                "<fg=red>%s\tShutdown signal detected - %s",
                $this->getNow(),
                self::class
            ));
        }
        if ($this->killFileExists($killFile)) {
            $output->writeln(sprintf(
                "<fg=red>%s\tKill File Detected - %s",
                $this->getNow(),
                self::class
            ));
        }
    }
}
