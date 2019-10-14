<?php

namespace Kobens\Gemini\Api\Param;

class ClientOrderId extends AbstractParam
{
    public function __construct(string $value = null)
    {
        // @todo https://docs.gemini.com/rest-api/#allowed-characters
        $this->value = $value;
    }
}

