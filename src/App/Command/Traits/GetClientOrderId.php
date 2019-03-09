<?php

namespace Kobens\Gemini\App\Command\Traits;

use Kobens\Gemini\Api\Param\ClientOrderId as Param;
use Kobens\Gemini\App\Command\Argument\ClientOrderId as Arg;
use Symfony\Component\Console\Input\InputInterface;

trait GetClientOrderId
{
    protected function getClientOrderId(InputInterface $input) : Param
    {
        return new Param($input->getArgument(Arg::NAME));
    }
}