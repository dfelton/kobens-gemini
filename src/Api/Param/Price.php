<?php

namespace Kobens\Gemini\Api\Param;

class Price extends AbstractParam
{
    public function __construct(string $value)
    {
        // @todo basic validation
        $this->value = $value;
    }
}

