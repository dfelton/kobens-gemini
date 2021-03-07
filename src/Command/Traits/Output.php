<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Traits;

use Symfony\Component\Console\Output\OutputInterface;

trait Output
{
    use GetNow;

    private function writeError(string $message, OutputInterface $output): void
    {
        $output->writeln(sprintf("%s\t<fg=red>%s</> -- %s", $this->getNow(), $message, self::class));
    }

    private function writeNotice(string $message, OutputInterface $output): void
    {
        $output->writeln(sprintf("%s\t<fg=cyan>%s</> -- %s", $this->getNow(), $message, self::class));
    }

    private function writeSuccess(string $message, OutputInterface $output): void
    {
        $output->writeln(sprintf("%s\t<fg=green>%s</> -- %s", $this->getNow(), $message, self::class));
    }

    private function writeWarning(string $message, OutputInterface $output): void
    {
        $output->writeln(sprintf("%s\t<fg=yellow>%s</> -- %s", $this->getNow(), $message, self::class));
    }
}
