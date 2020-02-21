<?php
/**
 * Logic for generating consistent order placement
 * points across a range of price points.
 */

require __DIR__.'/bootstrap.php';

use Kobens\Core\Config;
use Kobens\Core\Http\Request\Throttler;
use Kobens\Core\Http\Request\Throttler\Adapter\MariaDb;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Key;
use Kobens\Gemini\Api\Nonce;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\MakerOrCancel;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;

$pair = Pair::getInstance('btcusd');

$buy  = '0.00014000';
$save = '0.00000300';

$start  = '';
$end    = '';
$action = ''; // 'buy' | 'sell'


$sell          =  Subtract::getResult($buy, $save);
$increment     =  '2.50';
$sellAfterGain =  '0.025';

$feePercent    =  '0.001';
$holdPercent   =  '0.0035';


$holdFee = $totalProfitQuote = $totalSellBase = $totalSellQuote = $totalSellFees = $totalBuyBase = $totalBuyQuote = $totalBuyFees = '0';
$orders = [];
$buyPrice = $start;

$base  = $pair->getBase();
$quote = $pair->getQuote();

while (Compare::getResult($buyPrice, $end) !== Compare::RIGHT_LESS_THAN) {

    $amountQuote = Multiply::getResult($buy, $buyPrice);
    if (Compare::getResult('0', $amountQuote) !== Compare::LEFT_LESS_THAN) {
        throw new Exception('Invalid amount');
    }

    $fee = Multiply::getResult($amountQuote, $feePercent);
    $holdFee = Add::getResult($holdFee, Multiply::getResult($amountQuote, $holdPercent));

    /**
     * Determine the sell price based off
     *      - Gain goal
     *      - Quote minimum price increment
     *
     * If the exact sell price for the desired "sell after gain" were to result in a value that does not
     * comply with the minimum price increment of the quote asset, then round down the exact sell price for the quote
     * asset to the nearest minimum increment amount, and then increment the quote price increment by the minimum
     * allowed amount.
     */
    // FIXME This needs to be revised to be compatible with multiple pairs
    $precision = \strlen(\substr($sellAfterGain, \strpos($sellAfterGain, '.') + 1)) * 2; // Length of gain precision * length of USD precision

    $sellPriceExact = \bcmul($buyPrice, $sellAfterGain, $precision);
    $sellPriceExact .= \str_repeat('0',  $precision - \strlen(\substr($sellPriceExact, \strpos($sellPriceExact, '.') + 1)));

    $sellPriceRoundedDown = \bcadd($sellPriceExact, '0', 2) . \str_repeat('0', $precision - 2);

    $sellPrice = $sellPriceExact === $sellPriceRoundedDown
        ? \bcadd($sellPriceRoundedDown, '.00', 2)
        : \bcadd($sellPriceRoundedDown, '.01', 2);
    $sellPrice = \bcadd($buyPrice, $sellPrice, 2);

    $sellSubtotalQuote = Multiply::getResult($sellPrice, $sell);
    $sellFeeQuote      = Multiply::getResult($sellSubtotalQuote, $feePercent);
    $sellYieldQuote    = Subtract::getResult($sellSubtotalQuote, $sellFeeQuote);

    $positionProfitQuote = Subtract::getResult($sellYieldQuote, Add::getResult($amountQuote, $fee));

    $data = [
        'buy_price' => $buyPrice,
        "buy_amount_{$quote->getSymbol()}" => $amountQuote,
        "buy_amount_{$base->getSymbol()}" => $buy,
        'buy_fee' => $fee,
        'sell_price' => $sellPrice,
        "sell_amount_{$base->getSymbol()}" => $sell,
        'sell_subtotal' => $sellSubtotalQuote,
        'sell_fee' => $sellFeeQuote,
        "position_profits_{$quote->getSymbol()}" => $positionProfitQuote,
        "position_profits_{$base->getSymbol()}" => $save
    ];

    $orders[] = $data;

    $totalBuyFees = Add::getResult($totalBuyFees, $fee);
    $totalBuyQuote  = Add::getResult($totalBuyQuote,  $amountQuote);
    $totalBuyBase  = Add::getResult($totalBuyBase,  $buy);

    $totalSellBase  = Add::getResult($totalSellBase,  $sell);
    $totalSellFees = Add::getResult($totalSellFees, $sellFeeQuote);
    $totalSellQuote  = Add::getResult($totalSellQuote,  $sellSubtotalQuote);

    $totalProfitQuote = Add::getResult($totalProfitQuote, $positionProfitQuote);

    $buyPrice = Add::getResult($buyPrice, $increment);

}

unset(
    $data, $fee, $precision, $start, $end, $positionProfitQuote, $amountQuote,
    $buy, $sell, $sellFeeQuote, $sellSubtotalQuote, $sellYieldQuote, $sellPriceExact,
    $sellPriceRoundedDown, $sellPrice, $buyPrice, $feePercent, $holdPercent, $increment
);


$config = Config::getInstance();
$hostInterface = new Host($config->get('gemini')->api->host);
$privateThrottlerInterface = new Throttler(
    new MariaDb(new \Zend\Db\Adapter\Adapter($config->get('database')->toArray())),
    $hostInterface->getHost().'::private'
);
$keyInterface = new Key(
    $config->get('gemini')->api->key->public_key,
    $config->get('gemini')->api->key->secret_key
);
$nonceInterface = new Nonce();
$makerOrCancel = new MakerOrCancel($hostInterface, $privateThrottlerInterface, $keyInterface, $nonceInterface);
$getAvailableBalances = new GetAvailableBalances($hostInterface, $privateThrottlerInterface, $keyInterface, $nonceInterface);

unset($config, $keyInterface, $hostInterface, $privateThrottlerInterface, $nonceInterface);

if ($action === 'buy' || $action === 'sell') {
    $funds = $getAvailableBalances->getCurrency(
        ($action === 'buy' ? $quote->getSymbol() : $base->getSymbol())
    );

    $amountRequired = $action === 'buy' ? Add::getResult($totalBuyQuote, $holdFee) : $totalSellBase;
    if (Compare::getResult('0', Subtract::getResult($funds->available, $amountRequired)) === Compare::LEFT_GREATER_THAN) {
        echo "\nInsufficient funds for order(s).\nRequired: {$amountRequired}\nAvailable: {$funds->available}\n";
        exit(1);
    }

    if ($action === 'sell') {
        $orders = \array_reverse($orders);
    }

    for ($i = 0, $j = \count($orders); $i < $j; $i++) {
        $r = $makerOrCancel->place(
            $pair,
            $action,
            $orders[$i]["{$action}_amount_{$base->getSymbol()}"],
            $orders[$i]["{$action}_price"]
        );
        if ($r->is_live) {
            echo "order {$r->order_id} {$r->side} {$r->symbol} {$r->original_amount} @ {$r->price}\n";
        } else {
            \Zend\Debug\Debug::dump($r, 'Order Not Live:');
            exit(1);
        }
    }
}

\Zend\Debug\Debug::dump(
    [
        'order_first' => $action === 'sell' ? \end($orders) : $orders[0],
        'order_last' => \count($orders) > 1 ? ($action === 'sell' ? \reset($orders) : \end($orders)) : null,
        'order_count' => \count($orders),
        "buy_{$base->getSymbol()}" => $totalBuyBase,
        "buy_{$quote->getSymbol()}_hold" => Add::getResult($totalBuyQuote, $holdFee),
        "sell_{$quote->getSymbol()}" => $totalSellBase,
        "total_profit_{$quote->getSymbol()}" => $totalProfitQuote,
        "total_profit_{$base->getSymbol()}" => Multiply::getResult(\count($orders), $save),
    ],
    'Summary'
);

exit(0);

