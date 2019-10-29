<?php

require __DIR__.'/bootstrap.php';

use Kobens\Gemini\Api\Rest\Request\Order\Status\ActiveOrders;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\Cancel;

$symbol = 'btcusd';
$side   = '';
$amount = '';

$priceFrom = '';
$priceTo   = '';

$abort = false;
$orderIds = [];
foreach (\json_decode((new ActiveOrders())->getResponse()['body']) as $order) {
    if (   $order->symbol === $symbol
        && $order->side === $side
        && (float) $order->price >= (float) $priceFrom
        && (float) $order->price <= (float) $priceTo
    ) {
        if ($order->executed_amount === '0') {
            $orderIds[] = $order->order_id;
        } else {
            $abort = true;
            echo
                "Order ID {$order->order_id} at price of {$order->price} has executed {$order->executed_amount} of {$order->original_amount}. ",
                "Skipping for cancellation.\n";
        }
    }
}
unset($order);

echo "\n{$side} orders to be cancelled: ",\count($orderIds),"\n";

if ($abort) {
    echo "Not all orders have full original_amount remaining. Aborting.\n";
    exit(1);
}
if (   isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] === 'confirm'
    && isset($_SERVER['argv'][2]) && $_SERVER['argv'][2] === 'cancelNow'
) {
    echo "Starting cancellation in 10 seconds...\n";
    \sleep(10);
    foreach ($orderIds as $orderId) {
        $response = (new Cancel($orderId))->getResponse();
        $response['body'] = \json_decode($response['body']);
        if ($response['code'] !== 200) {
            \Zend\Debug\Debug::dump($response);
            exit(1);
        }
        echo "Order ID {$response['body']->order_id} at price of {$response['body']->price} cancelled.\n";
    }
} else {
    echo "Cancellation confirmation not detected. No action taken\n";
}
exit(0);

