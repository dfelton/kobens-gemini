<?php

namespace Kobens\Core\Exception;

class ActionRequiredException extends Exception
{
    public function __construct()
    {
        parent::__construct('Action is required.');
    }
}