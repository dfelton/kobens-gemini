<?php
namespace Kobens\Gemini\Api\Param;

use Kobens\Exchange\PairInterface;

class Symbol extends AbstractParam
{
    public function __construct(PairInterface $pair)
    {
        $this->value = $pair->getPairSymbol();
    }
}

