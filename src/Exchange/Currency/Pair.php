<?php

declare(strict_types=1);

namespace Kobens\Gemini\Exchange\Currency;

use Kobens\Currency\Currency;
use Kobens\Currency\Pair as CurrencyPair;
use Kobens\Exchange\PairInterface;

final class Pair extends CurrencyPair implements PairInterface
{
    private string $minOrderIncrement;
    private string $minOrderSize;
    private string $minPriceIncrement;

    private static array $pairs = [
        'btcusd' => ['base' => 'btc', 'quote' => 'usd', 'minOrderSize' => '0.00001', 'minOrderIncrement' => '0.00000001', 'minPriceIncrement' => '0.01'],
        'ethbtc' => ['base' => 'eth', 'quote' => 'btc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
        'ethusd' => ['base' => 'eth', 'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],
        'ltcbtc' => ['base' => 'ltc', 'quote' => 'btc', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.00001'],
        'ltceth' => ['base' => 'ltc', 'quote' => 'eth', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.0001'],
        'ltcusd' => ['base' => 'ltc', 'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.01'],
        'zecbtc' => ['base' => 'zec', 'quote' => 'btc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
        'zeceth' => ['base' => 'zec', 'quote' => 'eth', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],
        'zecltc' => ['base' => 'zec', 'quote' => 'ltc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],
        'zecusd' => ['base' => 'zec', 'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],
    ];

    /**
     * @var PairInterface[]
     */
    private static array $instances = [];

    private function __construct(string $symbol)
    {
        if (!\array_key_exists($symbol, self::$pairs)) {
            throw new \InvalidArgumentException("Unknown trading pair \"$symbol\"");
        }
        parent::__construct(
            Currency::getInstance(self::$pairs[$symbol]['base']),
            Currency::getInstance(self::$pairs[$symbol]['quote'])
        );
        $this->minOrderSize = self::$pairs[$symbol]['minOrderSize'];
        $this->minOrderIncrement = self::$pairs[$symbol]['minOrderIncrement'];
        $this->minPriceIncrement = self::$pairs[$symbol]['minPriceIncrement'];
    }

    public static function getInstance(string $symbol): PairInterface
    {
        if (!\array_key_exists($symbol, self::$instances)) {
            self::$instances[$symbol] = new self($symbol);
        }
        return self::$instances[$symbol];
    }

    /**
     * @return PairInterface[]
     */
    public static function getAllInstances(): array
    {
        foreach (\array_diff(\array_keys(self::$pairs), \array_keys(self::$instances)) as $symbol) {
            self::getInstance($symbol);
        }
        return self::$instances;
    }

    public function getMinOrderSize(): string
    {
        return $this->minOrderSize;
    }

    public function getMinOrderIncrement(): string
    {
        return $this->minOrderIncrement;
    }

    public function getMinPriceIncrement(): string
    {
        return $this->minPriceIncrement;
    }

    public function __get(string $name)
    {
        switch ($name) {
            case 'minOrderSize':
                return $this->minOrderSize;
            case 'minOrderIncrement':
                return $this->minOrderIncrement;
            case 'minPriceIncrement':
                return $this->minPriceIncrement;
            default:
                return parent::__get($name);
        }
    }
}
