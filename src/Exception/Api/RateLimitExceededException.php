<?php

namespace Kobens\Gemini\Exception\Api;

class RateLimitExceededException extends \Exception
{
    public function __construct(string $message = null, \Exception $previous = null)
    {
        parent::__construct($message, 429, $previous);
    }
}
