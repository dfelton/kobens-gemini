<?php

namespace Kobens\Gemini\App\Command\Traits;

use Kobens\Gemini\Api\Param\Symbol as Param;
use Kobens\Gemini\App\Command\Argument\Symbol as Arg;
use Kobens\Gemini\Exchange;
use Symfony\Component\Console\Input\InputInterface;

trait GetSymbol
{
    protected function getSymbol(InputInterface $input) : Param
    {
        return new Param((new Exchange())->getPair($input->getArgument(Arg::NAME)));
    }
}