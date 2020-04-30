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
use Kobens\Gemini\TradeRepeater\PricePointGenerator;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Subtract;

$pair = Pair::getInstance('btcusd');

$buy  = '0.00016000';
$save = '0.00000300';

$start  = '';
$end    = '';
$action = '';

$increment     =  '2.50';
$sellAfterGain =  '0.025';

$result = PricePointGenerator::get($pair, $buy, $start, $end, $increment, $sellAfterGain, $save);

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

$base = $pair->getBase();
$quote = $pair->getQuote();

$orders = $result->getPricePoints();

if ($action === 'buy' || $action === 'sell') {
    $funds = $getAvailableBalances->getBalance(
        ($action === 'buy' ? $pair->getQuote()->getSymbol() : $base->getSymbol())
    );

    $amountRequired = $action === 'buy'
        ? Add::getResult($result->getTotalBuyQuote(), $result->getTotalBuyFees())
        : $result->getTotalSellBase();

    if (Compare::getResult('0', Subtract::getResult($funds->getAvailable(), $amountRequired)) === Compare::LEFT_GREATER_THAN) {
        echo "\nInsufficient funds for order(s).\nRequired: {$amountRequired}\nAvailable: {$funds->getAvailable()}\n";
        exit(1);
    }

    if ($action === 'sell') {
        $orders = \array_reverse($orders);
    }

    for ($i = 0, $j = \count($orders); $i < $j; $i++) {
        $r = $makerOrCancel->place(
            $pair,
            $action,
            $action === 'buy' ? $orders[$i]->getBuyAmountBase() : $orders[$i]->getSellAmountBase(),
            $action === 'buy' ? $orders[$i]->getBuyPrice() : $orders[$i]->getSellPrice()
        );
        if ($r->is_live) {
            echo "order {$r->order_id} {$r->side} {$r->symbol} {$r->original_amount} @ {$r->price}\n";
        } else {
            \Zend\Debug\Debug::dump($r, 'Order Not Live:');
            exit(1);
        }
    }
}

/** @var PricePoint $first */
$first = $action === 'sell' ? \end($orders) : \reset($orders);
/** @var PricePoint $last */
$last  = $action === 'sell' ? \reset($orders) : \end($orders);

\Zend\Debug\Debug::dump(
    [
        'order_first' => !$first instanceof PricePoint ? null : [
            'buy_price' => $first->getBuyPrice(),
            "buy_amount_{$base->getSymbol()}" => $first->getBuyAmountBase(),
            "buy_amount_{$quote->getSymbol()}" => $first->getBuyAmountQuote(),
            "buy_fee" => $first->getBuyFee(),
            "buy_fee_hold" => $first->getBuyFeeHold(),
            "sell_price" => $first->getSellPrice(),
            "sell_amount_{$base->getSymbol()}" => $first->getSellAmountBase(),
            "sell_amount_{$quote->getSymbol()}" => $first->getSellAmountQuote(),
            "sell_fee" => $first->getSellFee(),
            "profit_{$base->getSymbol()}" => $first->getProfitBase(),
            "profit_{$quote->getSymbol()}" => $first->getProfitQuote(),
        ],
        'order_last'  => ! $last instanceof PricePoint ? null : [
            'buy_price' => $last->getBuyPrice(),
            "buy_amount_{$base->getSymbol()}" => $last->getBuyAmountBase(),
            "buy_amount_{$quote->getSymbol()}" => $last->getBuyAmountQuote(),
            "buy_fee" => $last->getBuyFee(),
            "buy_fee_hold" => $last->getBuyFeeHold(),
            "sell_price" => $last->getSellPrice(),
            "sell_amount_{$base->getSymbol()}" => $last->getSellAmountBase(),
            "sell_amount_{$quote->getSymbol()}" => $last->getSellAmountQuote(),
            "sell_fee" => $last->getSellFee(),
            "profit_{$base->getSymbol()}" => $last->getProfitBase(),
            "profit_{$quote->getSymbol()}" => $last->getProfitQuote(),
        ],
        'order_count' => \count($orders),
        "buy_{$base->getSymbol()}" => $result->getTotalBuyBase(),
        "buy_{$quote->getSymbol()}_hold" => Add::getResult($result->getTotalBuyFeesHold(), $result->getTotalBuyQuote()),
        "sell_{$base->getSymbol()}" => $result->getTotalSellBase(),
        "total_profit_{$quote->getSymbol()}" => $result->getTotalProfitQuote(),
        "total_profit_{$base->getSymbol()}" => $result->getTotalProfitBase(),
    ],
    'Summary'
);

exit(0);

