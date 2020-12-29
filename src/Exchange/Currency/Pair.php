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
        // Aave - https://aave.com/
        'aaveusd'  => ['base' => 'aave',  'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Amp Token - https://amptoken.org/
        'ampusd'   => ['base' => 'amp',   'quote' => 'usd', 'minOrderSize' => '10.0',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Balancer - https://balancer.finance/
        'balusd'   => ['base' => 'bal',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Basic Attention Token - https://basicattentiontoken.org/
        'bateth'   => ['base' => 'bat',   'quote' => 'eth', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0000001'],
        'batusd'   => ['base' => 'bat',   'quote' => 'usd', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
        'batbtc'   => ['base' => 'bat',   'quote' => 'btc', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00000001'],

        // BCash
        'bchusd'   => ['base' => 'bch',   'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],
        'bchbtc'   => ['base' => 'bch',   'quote' => 'btc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
        'bcheth'   => ['base' => 'bch',   'quote' => 'eth', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Bitcoin
        'btcusd'   => ['base' => 'btc',   'quote' => 'usd', 'minOrderSize' => '0.00001', 'minOrderIncrement' => '0.00000001', 'minPriceIncrement' => '0.01'],
        'btcdai'   => ['base' => 'btc',   'quote' => 'dai', 'minOrderSize' => '0.00001', 'minOrderIncrement' => '0.00000001', 'minPriceIncrement' => '0.01'],

        // Compound Governance Token
        'compusd'  => ['base' => 'comp',  'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // Curve DAO Token
        'crvusd'   => ['base' => 'crv',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Dai Stablecoin
        'daiusd'   => ['base' => 'dai',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Ethereum
        'ethbtc'   => ['base' => 'eth',   'quote' => 'btc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
        'ethdai'   => ['base' => 'eth',   'quote' => 'dai', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],
        'ethusd'   => ['base' => 'eth',   'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // Kyber Network
        'kncusd'   => ['base' => 'knc',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Filecoin
        'filusd'   => ['base' => 'fil',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Chainlink
        'linkbtc'  => ['base' => 'link',  'quote' => 'btc', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00000001'],
        'linketh'  => ['base' => 'link',  'quote' => 'eth', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0000001'],
        'linkusd'  => ['base' => 'link',  'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Litecoin
        'ltcbch'   => ['base' => 'ltc',   'quote' => 'bch', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.0001'],
        'ltcbtc'   => ['base' => 'ltc',   'quote' => 'btc', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.00001'],
        'ltceth'   => ['base' => 'ltc',   'quote' => 'eth', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.0001'],
        'ltcusd'   => ['base' => 'ltc',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.01'],

        // Decentraland
        'manausd'  => ['base' => 'mana',  'quote' => 'usd', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Maker
        'mkrusd'   => ['base' => 'mkr',   'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // Orchid
        'oxtbtc'   => ['base' => 'oxt',   'quote' => 'btc', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00000001'],
        'oxteth'   => ['base' => 'oxt',   'quote' => 'eth', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0000001'],
        'oxtusd'   => ['base' => 'oxt',   'quote' => 'usd', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // PAX Gold - https://www.paxos.com/paxgold/
        'paxgusd'  => ['base' => 'paxg',  'quote' => 'usd', 'minOrderSize' => '0.0001',  'minOrderIncrement' => '0.00000001', 'minPriceIncrement' => '0.01'],

        // RenVM - https://renproject.io/
        'renusd'   => ['base' => 'ren',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Storj - https://storj.io/
        'storjusd' => ['base' => 'storj', 'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Synthetix - https://www.synthetix.io/
        'snxusd'   => ['base' => 'snx',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Universal Market Access - https://umaproject.org/
        'umausd'  => ['base' => 'uma',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Uniswap - https://uniswap.org/
        'uniusd'  => ['base' => 'uni',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Yearn Finance - https://yearn.finance/
        'yfiusd'  => ['base' => 'yfi',   'quote' => 'usd', 'minOrderSize' => '0.00001', 'minOrderIncrement' => '0.00000001', 'minPriceIncrement' => '0.01'],

        // ZCash
        'zecbch'  => ['base' => 'zec',   'quote' => 'bch', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],
        'zecbtc'  => ['base' => 'zec',   'quote' => 'btc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
        'zeceth'  => ['base' => 'zec',   'quote' => 'eth', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],
        'zecltc'  => ['base' => 'zec',   'quote' => 'ltc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],
        'zecusd'  => ['base' => 'zec',   'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // 0x - https://0x.org/
        'zrxusd'  => ['base' => 'zrx',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
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
