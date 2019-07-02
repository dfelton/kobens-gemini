<?php

namespace Kobens\Gemini\Command\Argument;

use Kobens\Core\Command\Argument\ArgumentInterface;
use Symfony\Component\Console\Input\InputArgument;

final class Amount implements ArgumentInterface
{
    private const DEFAULT     = null;
    private const DESCRIPTION = 'Amount to interact with.';
    private const MODE        = InputArgument::REQUIRED;
    const NAME        = 'amount';

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
