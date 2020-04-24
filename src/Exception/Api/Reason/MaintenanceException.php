<?php

namespace Kobens\Gemini\Exception\Api\Reason;

use Kobens\Gemini\Exception;

final class MaintenanceException extends Exception
{
    public function __construct(string $message = 'The Gemini Exchange is currently undergoing maintenance.', int $code = 503, \Exception $e = null)
    {
        parent::__construct($message, $code, $e);
    }
}
