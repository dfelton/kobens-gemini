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

$checks = require __DIR__.'/checkOrdersData.php';

$config = Config::getInstance();
$hostInterface = new Host($config->get('gemini')->api->host);
$logger = new \Monolog\Logger('curl');
$logger->pushHandler(new \Monolog\Handler\StreamHandler($config->getLogDir() . '/curl.log'));

$loggerRequestPrivate = new \Monolog\Logger('request.private');
$loggerRequestPrivate->pushHandler(new \Monolog\Handler\StreamHandler($config->getLogDir() . '/request.private.log'));

$getOrders = new GetActiveOrders(
    new \Kobens\Gemini\Api\Rest\PrivateEndpoints\Request(
        $hostInterface,
        new Throttler(
            new MariaDb(new \Zend\Db\Adapter\Adapter($config->get('database')->toArray())),
            $hostInterface->getHost().'::private'
        ),
        new Key(
            $config->get('gemini')->api->key->public_key,
            $config->get('gemini')->api->key->secret_key
        ),
        new Nonce(),
        new \Kobens\Core\Http\Curl($logger),
        $loggerRequestPrivate
    )
);

$orderBatches = [];
foreach ($checks as $i => $check) {
    $symbol = $check['pair']->getSymbol();
    if (($orderBatches[$symbol] ?? false) === false) {
        $orderBatches[$symbol] = [];
    }
    $result = \Kobens\Gemini\TradeRepeater\PricePointGenerator::get(...array_values($check));
    foreach ($result->getPricePoints() as $pricePoint) {
        $orderBatches[$symbol][] = $pricePoint;
    }
}

foreach ($getOrders->getOrders() as $order) {
    foreach ($orderBatches as $symbol => $pricePoints) {
        if ($order->symbol !== $symbol) {
            break;
        }
        /** @var \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint $pricePoint */
        foreach ($pricePoints as $i => $pricePoint) {
            if ($order->side === 'buy') {
                if (
                    Compare::getResult($pricePoint->getBuyAmountBase(), $order->original_amount) === Compare::EQUAL &&
                    Compare::getResult($pricePoint->getBuyPrice(), $order->price) === Compare::EQUAL
                ) {
                    unset($orderBatches[$symbol][$i]);
                }
            } elseif ($order->side === 'sell') {
                if (
                    Compare::getResult($pricePoint->getSellAmountBase(), $order->original_amount) === Compare::EQUAL &&
                    Compare::getResult($pricePoint->getSellPrice(), $order->price) === Compare::EQUAL
                ) {
                    unset($orderBatches[$symbol][$i]);
                }
            }
        }
    }
}

$totalMissing = 0;
foreach ($orderBatches as $symbol => $pricePoints) {
    if ($pricePoints) {
        $checkPassed = false;
        echo "Missing $symbol orders:\n";
        foreach ($pricePoints as $pricePoint) {
            ++$totalMissing;
            echo 'Buy:  ', $pricePoint->getBuyAmountBase(), ' @ ', $pricePoint->getBuyPrice(),
                "\nSell: ", $pricePoint->getSellAmountBase(), ' @ ', $pricePoint->getSellPrice(), "\n\n";
        }
    }
}

echo $totalMissing === 0 ? "All expected orders found.\n" : "Total of $totalMissing expected orders found to be missing.\n";

