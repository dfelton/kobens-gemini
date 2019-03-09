<?php
namespace Kobens\Gemini\Api\Param;

class RefreshRate extends AbstractParam
{
    public function __construct(int $value)
    {
        $this->value = $value;
    }
}

