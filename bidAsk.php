<?php

use Kobens\Core\Config;
use Kobens\Core\Http\Request\Throttler;
use Kobens\Core\Http\Request\Throttler\Adapter\MariaDb;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Key;
use Kobens\Gemini\Api\Nonce;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrders;
use Kobens\Math\BasicCalculator\Compare;

require __DIR__.'/bootstrap.php';

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


$getOrders = new GetActiveOrders($hostInterface, $privateThrottlerInterface, $keyInterface, new Nonce());

while (true) {
    system('clear');
    echo (new \DateTime())->format('Y-m-d H:i:s'),"\n";
    try {
        $orders = $getOrders->getOrders();
        $ask = $bid = null;
        foreach ($orders as $order) {
            if ($order->symbol === 'btcusd') {
                if ($order->side === 'sell') {
                    if ($ask === null || Compare::getResult($ask, $order->price) === Compare::RIGHT_LESS_THAN) {
                        $ask = $order->price;
                    }
                } elseif ($order->side === 'buy') {
                    if ($bid === null || Compare::getResult($bid, $order->price) === Compare::LEFT_LESS_THAN) {
                        $bid = $order->price;
                    }
                }
            }
        }
        $spread = bcsub($ask ?? '0', $bid ?? '0', 2);
        $percent = $spread && $bid ? bcdiv($spread, $bid, 4) : '0';
        echo "Lowest Ask:\t$ask\nHighest Bid\t$bid\nSpread:\t\t\$$spread\nSpread:\t\t$percent%\n\n";
    } catch (\Kobens\Gemini\Exception $e) {
        echo $e->getMessage(),"\n";
    }
    sleep(5);
}
