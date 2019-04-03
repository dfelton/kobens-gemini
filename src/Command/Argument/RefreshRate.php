<?php

namespace Kobens\Gemini\Command\Argument;

use Kobens\Core\Command\Argument\ArgumentInterface;
use Symfony\Component\Console\Input\InputArgument;

final class RefreshRate implements ArgumentInterface
{
    private const DEFAULT     = 500000;
    private const DESCRIPTION = 'Refresh rate in micro seconds';
    private const MODE        = InputArgument::OPTIONAL;
    private const NAME        = 'refresh_rate';

    const MIN_VALUE   = 100000; // responsibility lies on Command object to enforce

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
