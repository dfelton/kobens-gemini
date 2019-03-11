<?php

namespace Kobens\Gemini\Exception\Api;

use Kobens\Gemini\Exception\Exception;

class InsufficientFundsException extends Exception
{
    const REASON = 'InsufficientFunds';
}
