<?php

namespace Kobens\Gemini\App\Command\Argument;

use Symfony\Component\Console\Input\InputArgument;

class Symbol implements ArgumentInterface
{
    const DEFAULT     = null;
    const DESCRIPTION = 'Trading pair symbol';
    const MODE        = InputArgument::REQUIRED;
    const NAME        = 'symbol';

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