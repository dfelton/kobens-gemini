<?php

namespace Kobens\Gemini\App\Command\Traits;

use Kobens\Gemini\App\Command\Argument\RefreshRate as Arg;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait RefreshRate
{
    protected function getRefreshRate(InputInterface $input, OutputInterface $output) : int
    {
        $refreshRate = (int) $input->getArgument('refresh_rate');
        if ($refreshRate < Arg::MIN_VALUE) {
            if ($output->isVerbose() || $output->isVeryVerbose()) {
                $output->writeln(\sprintf(
                    'Warning, minimum refresh rate is "%d". "%d" provided. Reverting to default refresh rate',
                    Arg::MIN_VALUE,
                    Arg::DEFAULT
                ));

            }
            $refreshRate = Arg::DEFAULT;
        }
        return $refreshRate;
    }

}