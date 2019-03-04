<?php

namespace Kobens\Gemini\Exchange\Pair;

use Kobens\Currency\Pair;
use Kobens\Exchange\PairInterface;
use Kobens\Currency\Crypto\Litecoin;
use Kobens\Currency\Fiat\USD;

class LTCUSD extends Pair implements PairInterface
{
    const MIN_ORDER_SIZE = '0.00001';
    const MIN_ORDER_INCREMENT = '0.00000001';
    const MIN_PRICE_INCREMENT = '0.01';

    public function __construct()
    {
        parent::__construct(new Litecoin(), new USD());
    }

    public function getMinOrderSize(): string
    {
        return self::MIN_ORDER_SIZE;
    }

    public function getMinOrderIncrement(): string
    {
        return self::MIN_ORDER_INCREMENT;
    }

    public function getMinPriceIncrement(): string
    {
        return self::MIN_PRICE_INCREMENT;
    }

}
