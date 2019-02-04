<?php

namespace Kobens\Core\Exception;

class ActionInvalidException extends Exception
{
    /**
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct(\sprintf('Invalid action "%s" specified.', $action));
    }
}