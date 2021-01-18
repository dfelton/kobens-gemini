<?php

declare(strict_types=1);

namespace Kobens\Gemini\Exception;

use Kobens\Gemini\Exception;

/**
 * @deprecated
 */
class InvalidResponseException extends Exception
{
    public function __construct(string $message, int $code = null, \Exception $previous = null)
    {
        trigger_error(sprintf('"%s" is deprecated', E_DEPRECATED));
        parent::__construct($message, $code, $previous);
    }
}
