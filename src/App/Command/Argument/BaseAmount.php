<?php

namespace Kobens\Gemini\App\Command\Argument;

use Symfony\Component\Console\Input\InputArgument;

class BaseAmount implements ArgumentInterface
{
    const DEFAULT     = null;
    const DESCRIPTION = 'Amount of Base Currency to interact with.';
    const MODE        = InputArgument::REQUIRED;
    const NAME        = 'base_amount';

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