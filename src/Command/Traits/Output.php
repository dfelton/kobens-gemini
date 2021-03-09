<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Traits;

use Symfony\Component\Console\Output\OutputInterface;

trait Output
{
    use GetNow;

    private function writeError(string $message, OutputInterface $output): void
    {
        $output->writeln(sprintf(
            "%s\t%s:\n\t\t\t<fg=red>%s</>",
            $this->getNow(),
            str_replace('Kobens\\Gemini\\Command\\Command\\', '', self::class),
            $message
        ));
    }

    private function writeNotice(string $message, OutputInterface $output): void
    {
        $output->writeln(sprintf(
            "%s\t%s:\n\t\t\t<fg=cyan>%s</>",
            $this->getNow(),
            str_replace('Kobens\\Gemini\\Command\\Command\\', '', self::class),
            $message
        ));
    }

    private function writeSuccess(string $message, OutputInterface $output): void
    {
        $output->writeln(sprintf(
            "%s\t%s:\n\t\t\t<fg=green>%s</>",
            $this->getNow(),
            str_replace('Kobens\\Gemini\\Command\\Command\\', '', self::class),
            $message
        ));
    }

    private function writeWarning(string $message, OutputInterface $output): void
    {
        $output->writeln(sprintf(
            "%s\t%s:\n\t\t\t<fg=yellow>%s</>",
            $this->getNow(),
            str_replace('Kobens\\Gemini\\Command\\Command\\', '', self::class),
            $message
        ));
    }
}
