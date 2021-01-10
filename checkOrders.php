<?php

use Kobens\Core\Config;
use Kobens\Core\Http\Request\Throttler;
use Kobens\Core\Http\Request\Throttler\Adapter\MariaDb;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Key;
use Kobens\Gemini\Api\Nonce;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrders;
use Kobens\Math\BasicCalculator\Compare;

(function(){ require __DIR__.'/bootstrap.php'; })();

function getActiveOrders(): GetActiveOrders
{
    $config = Config::getInstance();
    $hostInterface = new Host($config->get('gemini')->api->host);
    $logger = new \Monolog\Logger('curl');
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($config->getLogDir() . '/curl.log'));

    $loggerRequestPrivate = new \Monolog\Logger('request.private');
    $loggerRequestPrivate->pushHandler(new \Monolog\Handler\StreamHandler($config->getLogDir() . '/request.private.log'));

    return new GetActiveOrders(
        new \Kobens\Gemini\Api\Rest\PrivateEndpoints\Request(
            $hostInterface,
            new Throttler(
                new MariaDb(new \Zend\Db\Adapter\Adapter($config->get('kobens')->core->throttler->adapter->mariadb->toArray())),
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
}

function getOrderBatches(): array
{
    $data = [];
    foreach (require __DIR__.'/_checkOrdersData.php' as $check) {
        $symbol = $check['pair']->getSymbol();
        if (($data[$symbol] ?? false) === false) {
            $data[$symbol] = [];
        }
        $result = \Kobens\Gemini\TradeRepeater\PricePointGenerator::get(...array_values($check));
        foreach ($result->getPricePoints() as $pricePoint) {
            $data[$symbol][] = $pricePoint;
        }
    }
    return $data;
}

function examineOrders(array &$orderBatches): array
{
    $totalOrders = [];
    foreach (getActiveOrders()->getOrders() as $order) {
        if (
            !($order->client_order_id ?? null) ||
            strpos($order->client_order_id, 'repeater_') !== 0
        ) {
            continue;
        }
        foreach ($orderBatches as $symbol => $pricePoints) {
            if ($order->symbol !== $symbol) {
                continue;
            }

            if (!($totalOrders[$symbol] ?? false)) {
                $totalOrders[$symbol] = [
                    'sell' => 0,
                    'buy' => 0,
                ];
            }

            /** @var \Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint $pricePoint */
            foreach ($pricePoints as $i => $pricePoint) {
                if ($order->side === 'buy') {
                    if (
                        Compare::getResult($pricePoint->getBuyAmountBase(), $order->original_amount) === Compare::EQUAL &&
                        Compare::getResult($pricePoint->getBuyPrice(), $order->price) === Compare::EQUAL
                    ) {
                        unset($orderBatches[$symbol][$i]);
                        ++$totalOrders[$symbol]['buy'];
                    }
                } elseif ($order->side === 'sell') {
                    if (
                        Compare::getResult($pricePoint->getSellAmountBase(), $order->original_amount) === Compare::EQUAL &&
                        Compare::getResult($pricePoint->getSellPrice(), $order->price) === Compare::EQUAL
                    ) {
                        unset($orderBatches[$symbol][$i]);
                        ++$totalOrders[$symbol]['sell'];
                    }
                }
            }
        }
    }
    return $totalOrders;
}

$orderBatches = getOrderBatches();
$totalOrders = examineOrders($orderBatches);

$totalMissing = 0;
foreach ($orderBatches as $symbol => $pricePoints) {
    if ($pricePoints) {
        $totalOrders[$symbol] = 0;
        echo "Missing $symbol orders:\n";
        foreach ($pricePoints as $pricePoint) {
            ++$totalMissing;
            echo 'Buy:  ', $pricePoint->getBuyAmountBase(), ' @ ', $pricePoint->getBuyPrice(),
                "\tSell: ", $pricePoint->getSellAmountBase(), ' @ ', $pricePoint->getSellPrice(), "\n";
        }
    }
}

if ($totalMissing !== 0) {
    echo "Total of $totalMissing expected orders found to be missing.\n";
    exit;
}

echo "All expected orders found.\n";
foreach (array_keys($totalOrders) as $symbol) {
    echo "\n$symbol:\n\t{$totalOrders[$symbol]['sell']} sell orders\n\t{$totalOrders[$symbol]['buy']} buy orders\n";
}
