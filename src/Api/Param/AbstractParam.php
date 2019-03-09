<?php

namespace Kobens\Gemini\Api\Param;

abstract class AbstractParam implements ParamInterface
{
    protected $value;

    public function getValue()
    {
        return $this->value;
    }
}