<?php

use Kobens\Core\Config;
use Kobens\Core\EmergencyShutdown;
use Kobens\Gemini\Exchange;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Param\Amount;
use Kobens\Gemini\Api\Param\ClientOrderId;
use Kobens\Gemini\Api\Param\Price;
use Kobens\Gemini\Api\Param\Side;
use Kobens\Gemini\Api\Param\Symbol;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\Cancel;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder;
use Zend\Db\TableGateway\TableGateway;

require __DIR__.'/bootstrap.php';

if ((string) new Host() !== 'api.sandbox.gemini.com') {
    echo "\nActivity Generator only allowed on api.sandbox.gemini.com\n";
    exit(1);
}

$config = Config::getInstance();
$adapter = \Kobens\Core\Db::getAdapter();
$connection = $adapter->getDriver()->getConnection();
$shutdown = new EmergencyShutdown($config);
$tbl = new TableGateway('trade_repater', $adapter);
$exchange = new Exchange();

$sideBuy = new Side('buy');
$sideSell = new Side('sell');
$symbol = new Symbol($exchange->getPair('btcusd'));
$clientOrderId = new ClientOrderId();

function getRange(\Zend\Db\Adapter\Adapter $adapter): array
{
    /** @var \Zend\Db\Adapter\Driver\Pdo\Statement $minSell */
    $minSell = $adapter->query('select buy_price from trade_repeater order by buy_price asc limit 1')->execute()->current();
    $maxBuy = $adapter->query('select sell_price from trade_repeater order by sell_price desc limit 1')->execute()->current();
    return [
        'sell' => $minSell['buy_price'],
        'buy' => $maxBuy['sell_price'],
        'time' => \time(),
    ];
}

function getAmount(): Amount
{
    $overOneBtc = \rand(0, 100) > 90;
    $whole = $overOneBtc ? (string) \rand(1, 10) : '0';
    $satoshi = (string) \rand(1000, 99999999);
    $satoshi = \str_pad($satoshi, 8, \STR_PAD_LEFT);
    return new Amount("$whole.$satoshi");
}

function printException(\Exception $e): void
{
    do {
        echo
        "Code: {$e->getCode()}",
        "Message: {$e->getMessage()}\n",
        "Trace:\{$e->getTraceAsString()}\n\n"
        ;
        $e = $e->getPrevious();
        if ($e instanceof \Exception) {
            echo "Previous Exception:\n";
        }
    } while ($e instanceof \Exception);
}


$range = \getRange($adapter);

do {
    if (\time() - $range['time'] > 600) {
        $range = \getRange($adapter);
    }
    $side = \rand(0, 1) ? $sideBuy : $sideSell;
    $price = new Price($side === $sideBuy ? $range['buy'] : $range['sell']);
    $order = new NewOrder($side, $symbol, \getAmount(), $price, $clientOrderId);
    try {
        $response = \json_decode($order->getResponse()['body']);
        echo "{$response->side} {$response->original_amount}\n";
        if ($response->is_live === true) {
            (new Cancel($response->order_id))->getResponse();
        }
    } catch (\Exception $e) {
        \printException($e);
        exit(1);
    }
    \sleep(1);
} while ($shutdown->isShutdownModeEnabled() === false);

echo "\nShutdown Detected\n";
exit(0);

