<?php

namespace Kobens\Gemini\App\Command\Traits;

use Kobens\Gemini\Api\Param\Price as Param;
use Kobens\Gemini\App\Command\Argument\Price as Arg;
use Symfony\Component\Console\Input\InputInterface;

trait GetPrice
{
    protected function getPrice(InputInterface $input) : Param
    {
        return new Param($input->getArgument(Arg::NAME));
    }
}