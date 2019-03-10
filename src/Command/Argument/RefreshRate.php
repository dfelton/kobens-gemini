<?php

namespace Kobens\Gemini\Command\Argument;

use Symfony\Component\Console\Input\InputArgument;

class RefreshRate implements ArgumentInterface
{
    const DEFAULT     = 500000;
    const DESCRIPTION = 'Refresh rate in micro seconds';
    const MODE        = InputArgument::OPTIONAL;
    const NAME        = 'refresh_rate';

    const MIN_VALUE   = 100000; // responsibility lies on Command object to enforce

    public function getDefault()
    {
        return static::DEFAULT;
    }

    public function getDescription(): string
    {
        return static::DESCRIPTION;
    }

    public function getMode(): int
    {
        return static::MODE;
    }

    public function getName(): string
    {
        return static::NAME;
    }

}