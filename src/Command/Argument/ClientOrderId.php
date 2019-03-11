<?php

namespace Kobens\Gemini\Command\Argument;

use Kobens\Core\Command\Argument\ArgumentInterface;
use Symfony\Component\Console\Input\InputArgument;

class ClientOrderId implements ArgumentInterface
{
    const DEFAULT     = null;
    const DESCRIPTION = 'Client Order Id';
    const MODE        = InputArgument::OPTIONAL;
    const NAME        = 'client_order_id';

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