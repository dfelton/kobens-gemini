<?php

namespace Kobens\Gemini\App\Command\Traits;

use Kobens\Gemini\Api\Param\Side as Param;
use Kobens\Gemini\App\Command\Argument\Side as Arg;
use Symfony\Component\Console\Input\InputInterface;

trait GetSide
{
    protected function getSide(InputInterface $input) : Param
    {
        $side = $input->getArgument(Arg::NAME);
        switch (true) {
            case $side === 'bid' || $side === 'buy':
                $side = 'buy';
                break;
            case $side === 'ask' || $side === 'sell':
                $side = 'sell';
                break;
            default:
                throw new \Exception(\sprintf(
                    'Invalid order book side "%s"',
                    $side
                ));
        }
        return new Param($side);
    }
}