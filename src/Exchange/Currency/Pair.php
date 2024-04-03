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
        // 1inch - https://1inch.exchange/
        '1inchusd' => ['base' => '1inch', 'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Aave - https://aave.com/
        'aaveusd'  => ['base' => 'aave',  'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        'alcxusd'  => ['base' => 'alcx',  'quote' => 'usd', 'minOrderSize' => '0.00001', 'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // Amp Token - https://amptoken.org/
        'ampusd'   => ['base' => 'amp',   'quote' => 'usd', 'minOrderSize' => '10.0',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        'ankrusd'  => ['base' => 'ankr',  'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        'api3usd'  => ['base' => 'api3',  'quote' => 'usd', 'minOrderSize' => '0.03',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],
        'ashusd'   => ['base' => 'ash',   'quote' => 'usd', 'minOrderSize' => '0.005',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],
        'audiousd' => ['base' => 'audio', 'quote' => 'usd', 'minOrderSize' => '0.05',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],

        // Axie Infinity Token
        'axsusd'   => ['base' => 'axs',   'quote' => 'usd', 'minOrderSize' => '0.003',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // Balancer - https://balancer.finance/
        'balusd'   => ['base' => 'bal',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Basic Attention Token - https://basicattentiontoken.org/
        'batbtc'   => ['base' => 'bat',   'quote' => 'btc', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00000001'],
        'bateth'   => ['base' => 'bat',   'quote' => 'eth', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0000001'],
        'batusd'   => ['base' => 'bat',   'quote' => 'usd', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // BCash
        'bchbtc'   => ['base' => 'bch',   'quote' => 'btc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
        'bcheth'   => ['base' => 'bch',   'quote' => 'eth', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],
        'bchusd'   => ['base' => 'bch',   'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // Bancor Network Token - https://app.bancor.network/
        'bntusd'   => ['base' => 'bnt',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // BarnBridge (BOND)
        'bondusd'  => ['base' => 'bond',   'quote' => 'usd','minOrderSize' => '0.001',  'minOrderIncrement' => '0.000001',    'minPriceIncrement' => '0.0001'],

        // Bitcoin
        'btcdai'   => ['base' => 'btc',   'quote' => 'dai', 'minOrderSize' => '0.00001', 'minOrderIncrement' => '0.00000001', 'minPriceIncrement' => '0.01'],
        'btcusd'   => ['base' => 'btc',   'quote' => 'usd', 'minOrderSize' => '0.00001', 'minOrderIncrement' => '0.00000001', 'minPriceIncrement' => '0.01'],

        // Compound Governance Token
        'compusd'  => ['base' => 'comp',  'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // Curve DAO Token
        'crvusd'   => ['base' => 'crv',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        'ctxusd'   => ['base' => 'ctx',   'quote' => 'usd', 'minOrderSize' => '0.002',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Somnium Space (CUBE)
        'cubeusd'  => ['base' => 'cube',   'quote' => 'usd','minOrderSize' => '0.01',   'minOrderIncrement' => '0.000001',    'minPriceIncrement' => '0.0001'],

        // Dai Stablecoin
        'daiusd'   => ['base' => 'dai',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Dogecoin
        'dogeusd' => ['base'  => 'doge',  'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Enjin
        'enjusd'   => ['base' => 'enj',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Ethereum
        'ethbtc'   => ['base' => 'eth',   'quote' => 'btc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
        'ethdai'   => ['base' => 'eth',   'quote' => 'dai', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],
        'ethusd'   => ['base' => 'eth',   'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        'fetusd'   => ['base' => 'fet',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Filecoin
        'filusd'   => ['base' => 'fil',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Fantom
        'ftmusd'   => ['base' => 'ftm',   'quote' => 'usd', 'minOrderSize' => '0.03',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // The Graph
        'grtusd'   => ['base' => 'grt',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Injective Protocol (INJ)
        'injusd'   => ['base' => 'inj',    'quote' => 'usd','minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Kyber Network
        'kncusd'   => ['base' => 'knc',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Chainlink
        'linkbtc'  => ['base' => 'link',  'quote' => 'btc', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00000001'],
        'linketh'  => ['base' => 'link',  'quote' => 'eth', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0000001'],
        'linkusd'  => ['base' => 'link',  'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Livepeer (LPT)
        'lptusd'   => ['base' => 'lpt',    'quote' => 'usd','minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Loopring
        'lrcusd'   => ['base' => 'lrc',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Litecoin
        'ltcbch'   => ['base' => 'ltc',   'quote' => 'bch', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.0001'],
        'ltcbtc'   => ['base' => 'ltc',   'quote' => 'btc', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.00001'],
        'ltceth'   => ['base' => 'ltc',   'quote' => 'eth', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.0001'],
        'ltcusd'   => ['base' => 'ltc',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.00001',    'minPriceIncrement' => '0.01'],

        // Terra
        'lunausd'  => ['base' => 'luna',  'quote' => 'usd', 'minOrderSize' => '0.005',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // Decentraland
        'manausd'  => ['base' => 'mana',  'quote' => 'usd', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        'maskusd'  => ['base' => 'mask',  'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],
        // Polygon (MATIC)
        'maticusd' => ['base' => 'matic', 'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Moss Carbon Credit
        'mco2usd'  => ['base' => 'mco2',  'quote' => 'usd', 'minOrderSize' => '0.02',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // Mirror
        'mirusd'   => ['base' => 'mir',   'quote' => 'usd', 'minOrderSize' => '0.00001', 'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Maker
        'mkrusd'   => ['base' => 'mkr',   'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        'nmrusd'   => ['base' => 'nmr',   'quote' => 'usd', 'minOrderSize' => '0.003',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],

        // Orchid
        'oxtbtc'   => ['base' => 'oxt',   'quote' => 'btc', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00000001'],
        'oxteth'   => ['base' => 'oxt',   'quote' => 'eth', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0000001'],
        'oxtusd'   => ['base' => 'oxt',   'quote' => 'usd', 'minOrderSize' => '1.0',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // PAX Gold - https://www.paxos.com/paxgold/
        'paxgusd'  => ['base' => 'paxg',  'quote' => 'usd', 'minOrderSize' => '0.0001',  'minOrderIncrement' => '0.00000001', 'minPriceIncrement' => '0.01'],

        'qntusd'   => ['base' => 'qnt',   'quote' => 'usd', 'minOrderSize' => '0.0004',  'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],
        'radusd'   => ['base' => 'rad',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],
        'rareusd'  => ['base' => 'rare',  'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],

        // RenVM - https://renproject.io/
        'renusd'   => ['base' => 'ren',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // The Sandbox
        'sandusd'  => ['base' => 'sand',  'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        'shibusd'  => ['base' => 'shib',  'quote' => 'usd', 'minOrderSize' => '1000',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.000000001'],

        // SKALE Token - https://skale.network/token
        'sklusd'   => ['base' => 'skl',   'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // Smooth Love Potion
        'slpusd'   => ['base' => 'slp',   'quote' => 'usd', 'minOrderSize' => '0.5',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Synthetix - https://www.synthetix.io/
        'snxusd'   => ['base' => 'snx',   'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Storj - https://storj.io/
        'storjusd' => ['base' => 'storj', 'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // SushiSwap (SUSHI)
        'sushiusd' => ['base' => 'sushi', 'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Universal Market Access - https://umaproject.org/
        'umausd'  => ['base' => 'uma',    'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Uniswap - https://uniswap.org/
        'uniusd'  => ['base' => 'uni',    'quote' => 'usd', 'minOrderSize' => '0.01',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // TerraUSD
        'ustusd'  => ['base' => 'ust',    'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],

        'wcfgusd' => ['base'  => 'wcfg',  'quote' => 'usd', 'minOrderSize' => '0.05',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],

        'xtzusd'  => ['base' => 'xtz',    'quote' => 'usd', 'minOrderSize' => '0.02',    'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],

        // Yearn Finance - https://yearn.finance/
        'yfiusd'  => ['base' => 'yfi',    'quote' => 'usd', 'minOrderSize' => '0.00001', 'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // ZCash
        'zecbch'  => ['base' => 'zec',    'quote' => 'bch', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],
        'zecbtc'  => ['base' => 'zec',    'quote' => 'btc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],
        'zeceth'  => ['base' => 'zec',    'quote' => 'eth', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.0001'],
        'zecltc'  => ['base' => 'zec',    'quote' => 'ltc', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.001'],
        'zecusd'  => ['base' => 'zec',    'quote' => 'usd', 'minOrderSize' => '0.001',   'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.01'],

        // 0x - https://0x.org/
        'zrxusd'  => ['base' => 'zrx',    'quote' => 'usd', 'minOrderSize' => '0.1',     'minOrderIncrement' => '0.000001',   'minPriceIncrement' => '0.00001'],

        // The Rest...
        'aliusd'   => ['base' => 'ali',  'quote' => 'usd', 'minOrderSize' => '2.0',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.000001'],
        'apeusd'   => ['base' => 'ape',  'quote' => 'usd', 'minOrderSize' => '0.02',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'atomusd'  => ['base' => 'atom', 'quote' => 'usd', 'minOrderSize' => '0.01',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'avaxusd'  => ['base' => 'avax', 'quote' => 'usd', 'minOrderSize' => '0.005',  'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'chzusd'   => ['base' => 'chz',  'quote' => 'usd', 'minOrderSize' => '0.5',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        'cvcusd'   => ['base' => 'cvc',  'quote' => 'usd', 'minOrderSize' => '0.2',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        'dotusd'   => ['base' => 'dot',  'quote' => 'usd', 'minOrderSize' => '0.01',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.0001'],
        'elonusd'  => ['base' => 'elon', 'quote' => 'usd', 'minOrderSize' => '60000.0','minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00000000001'],
        'ensusd'   => ['base' => 'ens',  'quote' => 'usd', 'minOrderSize' => '0.002',  'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'ernusd'   => ['base' => 'ern',  'quote' => 'usd', 'minOrderSize' => '0.05',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.0001'],
        'eulusd'   => ['base' => 'eul',  'quote' => 'usd', 'minOrderSize' => '0.03',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.0001'],
        'fraxusd'  => ['base' => 'frax', 'quote' => 'usd', 'minOrderSize' => '0.1',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'fxsusd'   => ['base' => 'fxs',  'quote' => 'usd', 'minOrderSize' => '0.006',  'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'galausd'  => ['base' => 'gala', 'quote' => 'usd', 'minOrderSize' => '0.4',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        'galusd'   => ['base' => 'gal',  'quote' => 'usd', 'minOrderSize' => '0.04',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.0001'],
        'gfiusd'   => ['base' => 'gfi',  'quote' => 'usd', 'minOrderSize' => '0.04',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'gmtusd'   => ['base' => 'gmt',  'quote' => 'usd', 'minOrderSize' => '0.1',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        'imxusd'   => ['base' => 'imx',  'quote' => 'usd', 'minOrderSize' => '0.1',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        'indexusd' => ['base' => 'index','quote' => 'usd', 'minOrderSize' => '0.02',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'iotxusd'  => ['base' => 'iotx', 'quote' => 'usd', 'minOrderSize' => '3.0',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.000001'],
        'jamusd'   => ['base' => 'jam',  'quote' => 'usd', 'minOrderSize' => '10.0',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.0000001'],
        'kp3rusd'  => ['base' => 'kp3r', 'quote' => 'usd', 'minOrderSize' => '0.0001', 'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.01'],
        'ldousd'   => ['base' => 'ldo',  'quote' => 'usd', 'minOrderSize' => '0.02',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'lqtyusd'  => ['base' => 'lqty', 'quote' => 'usd', 'minOrderSize' => '0.03',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'mcusd'    => ['base' => 'mc',   'quote' => 'usd', 'minOrderSize' => '0.01',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'metisusd' => ['base' => 'metis','quote' => 'usd', 'minOrderSize' => '0.0007', 'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.01'],
        'mplusd'   => ['base' => 'mpl',  'quote' => 'usd', 'minOrderSize' => '0.007',  'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'orcausd'  => ['base' => 'orca', 'quote' => 'usd', 'minOrderSize' => '0.05',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'plausd'   => ['base' => 'pla',  'quote' => 'usd', 'minOrderSize' => '0.3',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        'qrdousd'  => ['base' => 'qrdo', 'quote' => 'usd', 'minOrderSize' => '0.04',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        'rayusd'   => ['base' => 'ray',  'quote' => 'usd', 'minOrderSize' => '0.03',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'rbnusd'   => ['base' => 'rbn',  'quote' => 'usd', 'minOrderSize' => '0.07',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        'rlyusd'   => ['base' => 'rly',  'quote' => 'usd', 'minOrderSize' => '0.2',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        'rndrusd'  => ['base' => 'rndr', 'quote' => 'usd', 'minOrderSize' => '0.02',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'samousd'  => ['base' => 'samo', 'quote' => 'usd', 'minOrderSize' => '10.0',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.0000001'],
        'solusd'   => ['base' => 'sol',  'quote' => 'usd', 'minOrderSize' => '0.001',  'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'spellusd' => ['base' => 'spell','quote' => 'usd', 'minOrderSize' => '5.0',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.0000001'],
        'tokeusd'  => ['base' => 'toke', 'quote' => 'usd', 'minOrderSize' => '0.002',  'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.001'],
        'zbcusd'   => ['base' => 'zbc',  'quote' => 'usd', 'minOrderSize' => '3.0',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],

        //'bicousd'  => ['base' => 'bico', 'quote' => 'usd', 'minOrderSize' => '0.2',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        //'dpiusd'   => ['base' => 'dpi',  'quote' => 'usd', 'minOrderSize' => '0.0006', 'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.01'],
        //'fidausd'  => ['base' => 'fida', 'quote' => 'usd', 'minOrderSize' => '0.06',   'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        //'mimusd'   => ['base' => 'mim',  'quote' => 'usd', 'minOrderSize' => '0.1',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.0001'],
        //'revvusd'  => ['base' => 'revv', 'quote' => 'usd', 'minOrderSize' => '1.0',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
        //'sbrusd'   => ['base' => 'sbr',  'quote' => 'usd', 'minOrderSize' => '1.0',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.000001'],
        //'truusd'   => ['base' => 'tru',  'quote' => 'usd', 'minOrderSize' => '0.8',    'minOrderIncrement' => '0.000001', 'minPriceIncrement' => '0.00001'],
    ];

    /**
     * @var PairInterface[]
     */
    private static array $instances = [];

    private function __construct(string $symbol)
    {
        $symbol= strtolower($symbol);
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
        ksort(self::$instances);
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
