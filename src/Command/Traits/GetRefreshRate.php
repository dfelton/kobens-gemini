<?php

namespace Kobens\Gemini\Command\Traits;

use Kobens\Gemini\Api\Param\RefreshRate as Param;
use Kobens\Gemini\Command\Argument\RefreshRate as Arg;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait GetRefreshRate
{
    protected function getRefreshRate(InputInterface $input, OutputInterface $output): Param
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
        return new Param($refreshRate);
    }
}
