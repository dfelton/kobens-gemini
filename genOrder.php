<?php
/**
 * Logic for generating consistent order placement
 * points across a range of price points.
 */

require __DIR__.'/bootstrap.php';

use Kobens\Exchange\TradeStrategies\Repeater\NewOrder;
use Kobens\Gemini\Exchange;
use Kobens\Gemini\Api\Param\Amount;
use Kobens\Gemini\Api\Param\ClientOrderId;
use Kobens\Gemini\Api\Param\Price;
use Kobens\Gemini\Api\Param\Side;
use Kobens\Gemini\Api\Param\Symbol;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;


$buyBtc  = '0.00005';
$saveBtc = '0.00000112';

$start       = '';
$end         = '';

$action = ''; // 'buy' | 'sell'


$cashLimit     =  null;
$sellBtc       =  Subtract::getResult($buyBtc, $saveBtc);
$increment     =  '2.50';
$sellAfterGain =  '0.025';

$feePercent    =  '0.001';
$holdPercent   =  '0.0035';


$holdFee = $totalProfitUsd = $totalSellBtc = $totalSellUsd = $totalSellFees = $totalBuyBtc = $totalBuyUsd = $totalBuyFees = '0';
$orders = [];
$buyPrice = $start;

while (\floatval($buyPrice) <= \floatval($end)) {

    $amountUsd = Multiply::getResult($buyBtc, $buyPrice);
    if (\floatval($amountUsd) === \floatval(0)) {
        throw new Exception('Invalid amount');
    }

    $fee = Multiply::getResult($amountUsd, $feePercent);
    $holdFee = Add::getResult($holdFee, Multiply::getResult($amountUsd, $holdPercent));

    if (\is_string($cashLimit) && \floatval(Subtract::getResult($cashLimit, Add::getResult($totalBuyUsd, Add::getResult($totalBuyFees, Add::getResult($fee, $amountUsd))))) < 0) {
        break;
    }

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
    $precision = \strlen(\substr($sellAfterGain, \strpos($sellAfterGain, '.') + 1)) * 2; // Length of gain precision * length of USD precision

    $sellPriceExact = \bcmul($buyPrice, $sellAfterGain, $precision);
    $sellPriceExact .= \str_repeat('0',  $precision - \strlen(\substr($sellPriceExact, \strpos($sellPriceExact, '.') + 1)));

    $sellPriceRoundedDown = \bcadd($sellPriceExact, '0', 2) . \str_repeat('0', $precision - 2);

    $sellPrice = $sellPriceExact === $sellPriceRoundedDown
        ? \bcadd($sellPriceRoundedDown, '.00', 2)
        : \bcadd($sellPriceRoundedDown, '.01', 2);
    $sellPrice = \bcadd($buyPrice, $sellPrice, 2);

    $sellSubtotalUsd = Multiply::getResult($sellPrice, $sellBtc);
    $sellFeeUsd = Multiply::getResult($sellSubtotalUsd, $feePercent);
    $sellYieldUsd = Subtract::getResult($sellSubtotalUsd, $sellFeeUsd);

    $positionProfitUsd = Subtract::getResult($sellYieldUsd, Add::getResult($amountUsd, $fee));

    $data = [
        'buy_price' => $buyPrice,
        'buy_amount_usd' => $amountUsd,
        'buy_amount_btc' => $buyBtc,
        //'buy_fee' => $fee,
        'buy_usd_with_fee' => Add::getResult($amountUsd, $fee),
        'sell_price' => $sellPrice,
        //'sell_amount_usd' => $sellSubtotalUsd,
        'sell_amount_btc' => $sellBtc,
        //'sell_fee' => $sellFeeUsd,
        //'sell_yield' => $sellYieldUsd,
        'position_profits_usd' => $positionProfitUsd,
        'position_profits_btc' => $saveBtc
    ];

    $orders[] = $data;

    $totalBuyFees = Add::getResult($totalBuyFees, $fee);
    $totalBuyUsd  = Add::getResult($totalBuyUsd, $amountUsd);
    $totalBuyBtc  = Add::getResult($totalBuyBtc, $buyBtc);

    $totalSellBtc  = Add::getResult($totalSellBtc, $sellBtc);
    $totalSellFees = Add::getResult($totalSellFees, $sellFeeUsd);
    $totalSellUsd  = Add::getResult($totalSellUsd, $sellSubtotalUsd);

    $totalProfitUsd = Add::getResult($totalProfitUsd, $positionProfitUsd);

    $buyPrice = Add::getResult($buyPrice, $increment);

    if (\floatval($buyPrice) > \floatval($end)) {
        break;
    }
}


if ($action === 'buy' || $action === 'sell') {

    // Verify funds on hand
    $funds = \json_decode((new Balances())->getResponse()['body']);
    if ($action === 'buy') {
        foreach ($funds as $fund) {
            if ($fund->currency === 'USD') {
                $amountRequired = Add::getResult($totalBuyUsd, $holdFee);
                if (\floatval($fund->available) < \floatval($amountRequired)) {
                    echo "\nInsufficient funds for orders.\nRequired: {$amountRequired}\nAvailable: {$fund->available}\n";
                    exit(1);
                }
                break;
            }
        }
    } else {
        foreach ($funds as $fund) {
            if ($fund->currency === 'BTC') {
                if (\floatval($fund->available) < \floatval($totalSellBtc)) {
                    echo "\nInsufficient funds for orders.\nRequired: {$totalSellBtc}\nAvailable: {$fund->available}\n";
                    exit(1);
                }
                break;
            }
        }
    }

    for ($i = 0, $j = \count($orders); $i < $j; $i++) {
        $order = new NewOrder(
            new Side($action),
            new Symbol((new Exchange())->getPair('btcusd')),
            new Amount($orders[$i]["{$action}_amount_btc"]),
            new Price($orders[$i]["{$action}_price"]),
            new ClientOrderId()
        );

        $response = $order->getResponse();
        $response['body'] = \json_decode($response['body']);
        if ($response['code'] !== 200) {
            \Zend\Debug\Debug::dump($response);
            break;
        }
        $r = $response['body'];
        \Zend\Debug\Debug::dump([
            'order_id' => $r->order_id,
            'symbol' => $r->symbol,
            'side' => $r->side,
            'amount' => $r->original_amount,
            'price' => $r->price,
        ]);
    }
}

\Zend\Debug\Debug::dump([
    'order_first' => $orders[0],
    'order_last' => \count($orders) > 1 ? \end($orders) : null,
    'order_count' => \count($orders),
    'buy_btc' => $totalBuyBtc,
    'buy_usd_hold' => Add::getResult($totalBuyUsd, $holdFee),
    'sell_btc' => $totalSellBtc,
    'total_profit_usd' => $totalProfitUsd,
    'total_profit_btc' => Multiply::getResult(\count($orders), $saveBtc),
]);

