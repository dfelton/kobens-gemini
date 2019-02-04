<?php
/**
 * Logic for generating consistent order placement 
 * points across a range of price points. 
 */

require __DIR__.'/vendor/autoload.php';

$buyBtc  = '0.00001';
$sellBtc = '0.00001';

$start       = '3000.00';
$end         = '4000.00';

$cashLimit   =   false;

$increment   =    '0.35';
$feePercent  =    '0.01';
$sellAfterGain =  '0.05';
$totalBuyFees  =  '0.00';
$totalBuyUsd   =  '0.00';
$totalBuyBtc   =  '0.00000000';
$totalSellFees =  '0.00';
$totalSellUsd  =  '0.00';
$totalSellBtc  =  '0.00000000';
$totalProfitUsd = '0';
$totalProfitBtc = '0';
$orders = [];

$buyPrice = $start;
while (floatval($buyPrice) <= floatval($end)) {

    $amountUsd = bcmul($buyBtc, (string) $buyPrice, 10);
    if (floatval($amountUsd) === floatval(0)) {
        throw new Exception('Invalid amount');
    }
    $fee = bcmul($amountUsd, $feePercent, 14);

    // TODO: What is this 9 doing here?
    $nextTotal = bcadd($totalBuyUsd, bcadd($totalBuyFees, bcadd($fee, $amountUsd, 14), 14), 14);
    if (is_string($cashLimit) && floatval(bcsub($cashLimit, $nextTotal, 9)) < 0) {
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
     *
     * TODO: Determine Base/Quote currency precisions, and calculate necessary scale
     * values for bc* methods below dynamically based off base/quote currencies.
     */
    $precision = strlen(substr($sellAfterGain, strpos($sellAfterGain, '.') + 1)) * 2; // Length of gain precision * length of USD precision

    $sellPriceExact = bcmul($buyPrice, $sellAfterGain, $precision);
    $sellPriceExact .= str_repeat('0',  $precision - strlen(substr($sellPriceExact, strpos($sellPriceExact, '.') + 1)));

    $sellPriceRoundedDown = bcadd($sellPriceExact, '0', 2) . str_repeat('0', $precision - 2);

    $sellPrice = $sellPriceExact === $sellPriceRoundedDown
        ? bcadd($sellPriceRoundedDown, '.00', 2)
        : bcadd($sellPriceRoundedDown, '.01', 2);
    $sellPrice = bcadd($buyPrice, $sellPrice, 2);

    $sellSubtotalUsd = bcmul($sellPrice, $sellBtc, 14);
    $sellFeeUsd = bcmul($sellSubtotalUsd, $feePercent, 14);
    $sellYieldUsd = bcsub($sellSubtotalUsd, $sellFeeUsd, 14);

    $positionProfitUsd = bcsub($sellYieldUsd, bcadd($amountUsd, $fee, 14), 14);
    $positionProfitBtc = bcsub($buyBtc, $sellBtc, 8);

    $data = [
        'buy_price' => $buyPrice,
        'buy_amount_usd' => $amountUsd,
        'buy_amount_btc' => $buyBtc,
        'buy_fee' => $fee,
        'bug_usd_with_fee' => bcadd($amountUsd, $fee, 14),
        'sell_price' => $sellPrice,
        'sell_amount_usd' => $sellSubtotalUsd,
        'sell_amount_btc' => $buyBtc,
        'sell_fee' => $sellFeeUsd,
        'sell_yield' => $sellYieldUsd,
        'position_profits_usd' => $positionProfitUsd,
        'position_profits_btc' => $positionProfitBtc,
    ];

    $orders[] = $data;

    $totalBuyFees = bcadd($totalBuyFees, $fee,       14);
    $totalBuyUsd  = bcadd($totalBuyUsd,  $amountUsd, 14);
    $totalBuyBtc  = bcadd($totalBuyBtc,  $buyBtc,     8);

    $totalSellBtc  = bcadd($totalSellBtc,  $sellBtc,         8);
    $totalSellFees = bcadd($totalSellFees, $sellFeeUsd,      14);
    $totalSellUsd  = bcadd($totalSellUsd,  $sellSubtotalUsd, 14);

    $totalProfitUsd = bcadd($totalProfitUsd, $positionProfitUsd, 14);
    $totalProfitBtc = bcadd($totalProfitBtc, $positionProfitBtc, 8);

    if (floatval(bcadd($buyPrice, $increment, 2)) > floatval($end)) {
        break;
    }
    $buyPrice = bcadd($buyPrice, $increment, 2);

}

$totalPositionFees = bcadd($totalBuyFees, $totalSellFees, 14);
$totalProfitExchangeAndMe = bcadd($totalPositionFees, $totalProfitUsd, 14);
$feesRatio = bcdiv($totalPositionFees, $totalProfitExchangeAndMe, 4);
$feesRatio = bcmul($feesRatio, '100', 2);


\Zend\Debug\Debug::dump([
    'order_first' => $orders[0],
    'order_last' => count($orders) > 1 ? end($orders) : null,
    'order_count' => count($orders),
    'increment' => $increment,
    'gain' => bcmul($sellAfterGain, '100', strlen(substr($sellAfterGain, strpos($sellAfterGain, '.') + 1)) - 2).'%',
    'buy_price_start' => $orders[0]['buy_price'],
    'buy_price_end' => $buyPrice,
    'buy_usd' => $totalBuyUsd,
    'buy_btc' => $totalBuyBtc,
    'buy_fees' => $totalBuyFees,
    'buy_usd_with_fees' => bcadd($totalBuyUsd, $totalBuyFees, 14),
    'sell_price_start' => $orders[0]['sell_price'],
    'sell_price_end' => $sellPrice,
    'sell_usd' => $totalSellUsd,
    'sell_btc' => $totalSellBtc,
    'sell_fees' => $totalSellFees,
    'total_fees' => $totalPositionFees,
    'total_profit_usd' => $totalProfitUsd,
    'total_profit_btc' => $totalProfitBtc,
    'fees_ate_profits' => $feesRatio . '%',
]);

// if ((bool) Cli::getArg('place')) {
//     $side = (string) Cli::getArg('side');
//     if (!in_array($side, ['buy', 'sell'])) {
//         throw new Exception('Invalid order book side');
//     }
//     foreach ($orders as $data) {
//         usleep(150000);
//         $order = new OrderNew($restKey, 'btcusd', $data[$side.'_price'], $data[$side.'_amount_btc'], $side);
//         $order->makeRequest();

//         $response = $order->getResponse();
//         $response = @json_decode($response);
//         if (!$response instanceOf stdClass) {
//             throw new Exception('No response from exchange server.');
//         }

//         $orderId = (int) $response->order_id;
//         if ($orderId > 0) {
//             echo ucfirst($side)." order placed for {$data[$side.'_amount_btc']}. Exchange Order Id: {$orderId}\n";
//         } else {

//             $filename = uniqid('err_'.$side.'_'.time().'_').'.log';
//             $handle = fopen($filename, 'w');

//             fwrite($handle, Zend_Debug::dump($order->getPayload(), 'payload', false));
//             fwrite($handle, PHP_EOL);
//             fwrite($handle, Zend_Debug::dump($response, 'response', false));

//             throw new Exception(
//                 "Unhandled response from exchange. Payload and response written to {$filename}"
//             );
//         }
//     }
// }

