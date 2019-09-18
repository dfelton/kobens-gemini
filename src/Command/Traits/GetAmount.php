<?php

namespace Kobens\Gemini\Command\Traits;

use Kobens\Gemini\Api\Param\Amount as Param;
use Kobens\Gemini\Command\Argument\Amount as Arg;
use Symfony\Component\Console\Input\InputInterface;

trait GetAmount
{
    protected function getAmount(InputInterface $input): Param
    {
        return new Param($input->getArgument(Arg::NAME));
    }
}
