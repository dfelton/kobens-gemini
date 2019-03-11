<?php

namespace Kobens\Gemini\Exception\Api;

use Kobens\Gemini\Exception\Exception;

class InvalidSignatureException extends Exception
{
    const REASON = 'InvalidSignature';
}
