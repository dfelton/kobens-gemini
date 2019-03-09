<?php
namespace Kobens\Gemini\Api\Param;

class Side extends AbstractParam
{
    public function __construct(string $side)
    {
        if ($side !== 'buy' && $side !== 'sell') {
            throw new \Exception(\sprintf('Invalid side "%s"', $side));
        }
        $this->value = $side;
    }
}

