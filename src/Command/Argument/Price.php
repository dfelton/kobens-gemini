<?php

namespace Kobens\Gemini\Command\Argument;

use Symfony\Component\Console\Input\InputArgument;

class Price implements ArgumentInterface
{
    const DEFAULT     = null;
    const DESCRIPTION = 'Price to interact with.';
    const MODE        = InputArgument::REQUIRED;
    const NAME        = 'price';

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