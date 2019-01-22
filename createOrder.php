<?php
/**
 * Creates orders
 */

require_once 'lib/cli.php';
require_once 'env/'.Cli::getArg('env').'.php';
require_once 'lib/orderNew.php';


/** @var RestKey $restKey */
global $restKey;

$buyBtc = '0.00001';

$cashLimit = '20.00';

$start       = '3423.85';
$end         = '3439.60';

$increment   =    '0.35';
$feePercent  =    '0.01';
$sellAfterGain =  '0.05';
$totalBuyFees  =  '0.00';
$totalBuyUsd   =  '0.00';
$totalBuyBtc   =  '0.00000000';
$totalSellFees =  '0.00';
$totalSellUsd  =  '0.00';
$totalSellBtc  =  '0.00000000';
$totalOrders = 0;
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

    $nextTotal = bcadd($totalBuyUsd, bcadd($totalBuyFees, bcadd($fee, $amountUsd, 14), 14), 14);
    if (floatval(bcsub($cashLimit, $nextTotal, 9)) < 0) {
        break;
    }

    $sellPrice = bcmul($buyPrice, $sellAfterGain, 2);
    $sellPrice = bcadd($sellPrice, $buyPrice, 2);
    $sellPrice = bcadd($sellPrice, '0.01', 2);

    $sellBtc = $buyBtc;

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
        'sell_price' => $sellPrice,
        'sell_amount_usd' => $sellSubtotalUsd,
        'sell_amount_btc' => $buyBtc,
        'sell_fee' => $sellFeeUsd,
        'sell_yield' => $sellYieldUsd,
        'position_profits_usd' => $positionProfitUsd,
        'position_profits_btc' => $positionProfitBtc,
    ];

    $orders[] = $data;

    $totalBuyFees = bcadd($totalBuyFees, $fee, 14);
    $totalBuyUsd = bcadd($totalBuyUsd, $amountUsd, 14);
    $totalBuyBtc = bcadd($totalBuyBtc, $buyBtc, 8);

    $totalSellBtc = bcadd($totalSellBtc, $sellBtc, 8);
    $totalSellFees = bcadd($totalSellFees, $sellFeeUsd, 14);
    $totalSellUsd = bcadd($totalSellUsd, $sellSubtotalUsd, 14);

    $totalProfitUsd = bcadd($totalProfitUsd, $positionProfitUsd, 14);
    $totalProfitBtc = bcadd($totalProfitBtc, $positionProfitBtc, 8);
    $totalOrders++;

    if (floatval(bcadd($buyPrice, $increment, 2)) > floatval($end)) {
        break;
    }
    $buyPrice = bcadd($buyPrice, $increment, 2);
}

$totalPositionFees = bcadd($totalBuyFees, $totalSellFees, 14);

if ((bool) Cli::getArg('debug')) {
    Zend_Debug::dump([
        'orders' => $orders,
        'limit_start' => $start,
        'limit_end' => $end,
        'start_buy_price' => $orders[0]['buy_price'],
        'start_sell_price' => $orders[0]['sell_price'],
        'end_buy_price' => $buyPrice,
        'end_sell_price' => $sellPrice,
        'cash_limit' => $cashLimit,
        'increment' => $increment,
        'ending_price' => $buyPrice,
        'total_orders' => $totalOrders,
        'total_buy_usd' => $totalBuyUsd,
        'total_buy_fees' => $totalBuyFees,
        'total_buy_usd_with_fees' => bcadd($totalBuyUsd, $totalBuyFees, 14),
        'total_buy_btc' => $totalBuyBtc,
        'total_sell_usd' => $totalSellUsd,
        'total_sell_btc' => $totalSellBtc,
        'total_sell_fees' => $totalSellFees,
        'total_position_fees' => $totalPositionFees,
        'total_profit_usd' => $totalProfitUsd,
        'total_profit_btc' => $totalProfitBtc,
        'fees_to_profit_ratio' => bcmul(bcdiv($totalPositionFees, $totalProfitUsd, 4), '100', 2) . '%',
    ]);
}

if ((bool) Cli::getArg('place')) {
    $side = Cli::getArg('side');
    if (!in_array($side, ['buy','sell'])) {
        die("\n\tInvalid order book side.");
    }
    foreach ($orders as $data) {
        usleep(150000);
        $order = new OrderNew($restKey, 'btcusd', $data[$side.'_price'], $data[$side.'_amount_btc'], $side);
        $order->makeRequest();
        Zend_Debug::dump(json_decode($order->getResponse()));
    }
}


