<?php

namespace Kobens\Gemini\Exchange\Pair;

use Kobens\Currency\Pair;
use Kobens\Exchange\PairInterface;
use Kobens\Currency\Crypto\{Ethereum, Litecoin};

class LTCETH extends Pair implements PairInterface
{
    const MIN_ORDER_SIZE = '0.01';
    const MIN_ORDER_INCREMENT = '0.00001';
    const MIN_PRICE_INCREMENT = '0.0001';

    public function __construct()
    {
        parent::__construct(new Litecoin(), new Ethereum());
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
