<?php

require __DIR__.'/bootstrap.php';

use Kobens\Core\Config;
use Kobens\Core\Http\Request\Throttler;
use Kobens\Core\Http\Request\Throttler\Adapter\MariaDb;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Key;
use Kobens\Gemini\Api\Nonce;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelOrder;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrders;



$symbol = 'btcusd';
$side   = '';
$amount = '';

$priceFrom = '';
$priceTo   = '';



$abort = false;
$orderIds = [];

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
$activeOrders = new GetActiveOrders($hostInterface, $privateThrottlerInterface, $keyInterface, $nonceInterface);
$cancelOrder = new CancelOrder($hostInterface, $privateThrottlerInterface, $keyInterface, $nonceInterface);
unset ($nonceInterface, $keyInterface, $privateThrottlerInterface, $hostInterface, $config);

foreach ($activeOrders->getOrders() as $order) {
    if (   $order->symbol === $symbol
        && $order->side === $side
        && $order->original_amount === $amount
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
        $response = $cancelOrder->cancel($orderId);
        echo "Order ID {$response->order_id} at price of {$response->price} cancelled.\n";
    }
} else {
    echo "Cancellation confirmation not detected. No action taken\n";
}
exit(0);

