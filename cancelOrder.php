<?php

require __DIR__.'/vendor/autoload.php';

use Kobens\Core\Config;
use Kobens\Exchange\Exchange\Mapper;
use Kobens\Gemini\Exchange;
use Kobens\Gemini\Api\Rest\Request\Order\Status\ActiveOrders;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\Cancel;

try {
    $config = Config::getInstance();
    $config->setRootDir(__DIR__);
    $config->setConfig(__DIR__.'/env/config.xml');
    new Mapper([
        'gemini' => Exchange::class
    ]);
} catch (Exception $e) {
    echo \sprintf(
        'Initialization Error: %s',
        $e->getMessage()
    );
    exit(1);
}


$symbol = 'btcusd';
$side   = '';
$amount = '';

$priceFrom = '';
$priceTo   = '';

exit;

$ordersToCancel = [];
foreach (json_decode((new ActiveOrders())->getResponse()['body']) as $order) {
    if ($order->symbol === $symbol && $order->side === $side && $order->original_amount === $amount && $order->remaining_amount = $amount) {
        if ((float) $order->price >= (float) $priceFrom && (float) $order->price <= (float) $priceTo) {
            \Zend\Debug\Debug::dump($order);exit;
            $ordersToCancel[] = $order->order_id;
        }
    }
}

\Zend\Debug\Debug::dump(count($ordersToCancel));exit;

foreach ($ordersToCancel as $orderId) {
    $response = (new Cancel($orderId))->getResponse();
    $response['body'] = json_decode($response['body']);
    \Zend\Debug\Debug::dump($response);
    if ($response['code'] !== 200) {
        break;
    }
}

