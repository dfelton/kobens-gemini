<?php

namespace Kobens\Gemini\Command\Traits;

use Kobens\Gemini\Exchange;
use Kobens\Gemini\Api\Param\Symbol as Param;
use Kobens\Gemini\Command\Argument\Symbol as Arg;
use Symfony\Component\Console\Input\InputInterface;

trait GetSymbol
{
    protected function getSymbol(InputInterface $input): Param
    {
        return new Param((new Exchange())->getPair($input->getArgument(Arg::NAME)));
    }
}
