<?php

namespace Kobens\Gemini\Command\Argument;

use Symfony\Component\Console\Input\InputArgument;

class Side implements ArgumentInterface
{
    const DEFAULT     = null;
    const DESCRIPTION = 'Bid or Ask (Buy or Sell)';
    const MODE        = InputArgument::REQUIRED;
    const NAME        = 'side';

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