<?php

namespace Kobens\Gemini\Command\Argument;

use Kobens\Core\Command\Argument\ArgumentInterface;
use Symfony\Component\Console\Input\InputArgument;

final class ClientOrderId implements ArgumentInterface
{
    private const DEFAULT     = null;
    private const DESCRIPTION = 'Client Order Id';
    private const MODE        = InputArgument::OPTIONAL;
    private const NAME        = 'client_order_id';

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
