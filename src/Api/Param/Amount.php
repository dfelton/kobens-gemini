<?php
namespace Kobens\Gemini\Api\Param;

class Amount extends AbstractParam
{
    public function __construct(string $value)
    {
        // @todo basic validation
        $this->value = $value;
    }
}

