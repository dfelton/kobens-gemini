<?php

namespace Kobens\Gemini\Api\Param;

class ClientOrderId extends AbstractParam
{
    public function __construct(string $value = null)
    {
        // @todo validation
        $this->value = $value;
    }
}

