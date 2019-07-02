<?php

namespace Kobens\Gemini\Command\Argument;

use Symfony\Component\Console\Input\InputArgument;
use Kobens\Core\Command\Argument\ArgumentInterface;

final class Symbol implements ArgumentInterface
{
    private const DEFAULT     = null;
    private const DESCRIPTION = 'Trading pair symbol';
    private const MODE        = InputArgument::REQUIRED;
    const NAME        = 'symbol';

    public function getDefault()
    {
        return self::DEFAULT;
    }

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    public function getMode(): int
    {
        return self::MODE;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
