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
    $range = [
        'sell' => null,
        'buy' => null,
        'time' => \time(),
    ];
    $prices = $adapter->query('select buy_price,sell_price from trade_repeater')->execute();
    foreach ($prices as $price) {
        if ($range['sell'] === null || (float) $range['sell'] > (float) $price['buy_price']) {
            $range['sell'] = $price['buy_price'];
        }
        if ($range['buy'] === null || (float) $range['buy'] < (float) $price['sell_price']) {
            $range['buy'] = $price['sell_price'];
        }
    }
    return $range;
}

function getAmount(): Amount
{
    $overOneBtc = \rand(0, 100) > 90;
    $whole = $overOneBtc ? (string) \rand(1, 9) : '0';
    $satoshi = (string) \rand(1000, 99999999);
    $satoshi = \str_pad($satoshi, 8, \STR_PAD_LEFT);
    return new Amount("$whole.$satoshi");
}

function printException(\Exception $e): void
{
    do {
        echo
            "Code: {$e->getCode()}\n",
            "Class: ".\get_class($e)."\n",
            "Message: {$e->getMessage()}\n",
            "Trace: {$e->getTraceAsString()}\n\n"
        ;
        $e = $e->getPrevious();
        if ($e instanceof \Exception) {
            echo "Previous Exception:\n";
        }
    } while ($e instanceof \Exception);
}

function placeOrder(NewOrder $order): void
{
    $response = \json_decode($order->getResponse()['body']);
    echo
        \str_pad($response->side, 5, ' ', \STR_PAD_RIGHT),
        \str_pad($response->executed_amount, 12, ' ', \STR_PAD_RIGHT);
    if ($response->executed_amount !== $response->original_amount) {
        echo " of {$response->original_amount}";
    }
    $avg = (string) \round((float) $response->avg_execution_price, 2);
    echo " at average price of {$avg}\n";
    if ($response->is_live === true) {
        (new Cancel($response->order_id))->getResponse();
    }
}


$range = \getRange($adapter);

do {
    if (\time() - $range['time'] > 600) {
        $range = \getRange($adapter);
    }
    $side = \rand(0, 1) ? $sideBuy : $sideSell;
    $order = new NewOrder($side, $symbol, \getAmount(), new Price($range[$side->getValue()]), $clientOrderId);
    try {
        \placeOrder($order);
    } catch (\Kobens\Core\Exception\ConnectionException $e) {
        // swallow exception
    } catch (\Kobens\Gemini\Exception\InvalidResponseException $e) {
        // Insufficient Funds. Buy or sell to restore some
        if ($e->getCode() === 406) {
            $side = $side === $sideBuy ? $sideSell : $sideBuy;
            $order = new NewOrder($side, $symbol, new Amount('9'), new Price($range[$side->getValue()]), $clientOrderId);
            try {
                \placeOrder($order);
            } catch (\Exception $e) {
                \printException($e);
                exit(1);
            }
        } else {
            \printException($e);
            exit(1);
        }
    } catch (\Exception $e) {
        \printException($e);
        exit(1);
    }
    \sleep(1);
} while ($shutdown->isShutdownModeEnabled() === false);

echo "\nShutdown Detected\n";
exit(0);

