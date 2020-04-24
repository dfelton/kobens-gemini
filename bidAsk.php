<?php

declare(strict_types=1);

use Kobens\Core\Config;
use Kobens\Core\Http\Request\Throttler;
use Kobens\Core\Http\Request\Throttler\Adapter\MariaDb;
use Kobens\Gemini\Exchange;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Key;
use Kobens\Gemini\Api\Nonce;
use Kobens\Gemini\Api\Market\GetPrice;
use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrders;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrdersInterface;
use Kobens\Gemini\Api\Rest\PublicEndpoints\Ticker;
use Kobens\Math\BasicCalculator\Compare;

require __DIR__.'/bootstrap.php';

$config = Config::getInstance();
$exchangeInterface = new Exchange();
$hostInterface = new Host($config->get('gemini')->api->host);
$publicThrottlerInterface = new Throttler(
    new MariaDb(new \Zend\Db\Adapter\Adapter($config->get('database')->toArray())),
    $hostInterface->getHost().'::public'
);
$privateThrottlerInterface = new Throttler(
    new MariaDb(new \Zend\Db\Adapter\Adapter($config->get('database')->toArray())),
    $hostInterface->getHost().'::private'
);
$tickerInterface = new Ticker($hostInterface, $publicThrottlerInterface);
$keyInterface = new Key(
    $config->get('gemini')->api->key->public_key,
    $config->get('gemini')->api->key->secret_key
);

function loop(GetActiveOrdersInterface $getOrders, GetPriceInterface $getPrice, string $symbol): array
{
    $data = [];
    try {
        $data[] = orders($getOrders, $symbol);
        $data[] = price($getPrice, $symbol);
    } catch (\Kobens\Gemini\Exception $e) {
        $data[] = $e->getMessage();
    }
    return $data;
}

function orders(GetActiveOrdersInterface $getOrders, string $symbol): string
{
    $ask = $bid = null;
    foreach ($getOrders->getOrders() as $order) {
        if ($order->symbol === $symbol) {
            if ($order->side === 'sell'
            ) {
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
    return (string) sprintf(
        "Lowest Ask:\t %s \nHighest Bid\t %s \nSpread:\t\t\$ %s \nSpread:\t\t%s",
        $ask ?? '',
        $bid ?? '',
        $spread,
        $percent
    );
}

function price(GetPriceInterface $getPrice, string $symbol): string
{
    $result = $getPrice->getResult($symbol);
    return sprintf(
        "Current Bid:\t%s\nCurrent Ask:\t%s",
        $result->getBid(),
        $result->getAsk()
    );
}


$getOrders = new GetActiveOrders($hostInterface, $privateThrottlerInterface, $keyInterface, new Nonce());
$getPrice = new GetPrice($exchangeInterface, $tickerInterface);
$loop = true;
while ($loop) {
    try {
        $result = implode(PHP_EOL . PHP_EOL, loop($getOrders, $getPrice, 'btcusd'));
        system('clear');
        echo sprintf(
            "%s\n\n%s\n",
            (new \DateTime())->format('Y-m-d H:i:s'),
            $result
        );
        sleep(1);
    } catch (\Exception $e) {
        $loop = false;
        echo $e->getMessage(),PHP_EOL,$e->getTraceAsString(),PHP_EOL;
    }
}
