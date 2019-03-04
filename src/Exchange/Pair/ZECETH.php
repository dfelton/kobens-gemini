<?php

namespace Kobens\Gemini\Exchange\Pair;

use Kobens\Currency\Pair;
use Kobens\Exchange\PairInterface;
use Kobens\Currency\Crypto\{Ethereum, Zcash};

class ZECETH extends Pair implements PairInterface
{
    const MIN_ORDER_SIZE = '0.001';
    const MIN_ORDER_INCREMENT = '0.000001';
    const MIN_PRICE_INCREMENT = '0.0001';

    public function __construct()
    {
        parent::__construct(new Zcash(), new Ethereum());
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
