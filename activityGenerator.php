<?php

use Kobens\Core\Config;
use Kobens\Core\EmergencyShutdown;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Core\Http\Request\Throttler;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Key;
use Kobens\Gemini\Api\Nonce;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\ImmediateOrCancel;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\ImmediateOrCancelInterface;
use Kobens\Gemini\Exception\Api\Reason\InsufficientFundsException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\Exchange\Currency\Pair;

require __DIR__.'/bootstrap.php';

$config = Config::getInstance();
$host = new Host($config->get('gemini')->api->host);

if ($host->getHost() !== 'api.sandbox.gemini.com') {
    echo "\nActivity Generator only allowed on api.sandbox.gemini.com\n";
    exit(1);
}

$adapter = \Kobens\Core\Db::getAdapter();

$shutdownDir = \array_key_exists(1, $_SERVER['argv']) && \is_dir($_SERVER['argv'][1])
    ? $_SERVER['argv'][1]
    : __DIR__.DIRECTORY_SEPARATOR.'var'
;

$shutdown = new EmergencyShutdown($shutdownDir);

$immediateOrCancel = new ImmediateOrCancel(
    $host,
    new Throttler($host->getHost().'::private'),
    new Key(
        $config->get('gemini')->api->key->public_key,
        $config->get('gemini')->api->key->secret_key
    ),
    new Nonce()
);

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

function getAmount(): string
{
    $overOneBtc = \rand(0, 100) > 98;
    $whole = $overOneBtc ? (string) \rand(1, 5) : '0';
    $satoshi = (string) (\rand(1, 10) > 1 ? \rand(1000, 999999): \rand(1000, 49999999));
    $satoshi = \str_pad($satoshi, 8, \STR_PAD_LEFT);
    return "$whole.$satoshi";
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

function placeOrder(ImmediateOrCancelInterface $immediateOrCancel, string $symbol, string $side, string $amount, string $price): void
{
    $data = $immediateOrCancel->place(Pair::getInstance($symbol), $side, $amount, $price);
    echo
        \str_pad($data->side, 5, ' ', \STR_PAD_RIGHT),
        \str_pad($data->executed_amount, 12, ' ', \STR_PAD_RIGHT);
    if ($data->executed_amount !== $data->original_amount) {
        echo " of {$data->original_amount}";
    }
    $avg = (string) \round((float) $data->avg_execution_price, 2);
    echo " at average price of {$avg}\n";
}


$range = \getRange($adapter);
$sleep = 0;

do {
    if ($sleep) {
        \sleep($sleep);
    }
    if (\time() - $range['time'] > 600) {
        $range = \getRange($adapter);
    }
    $side = \rand(0, 1) ? 'buy' : 'sell';
    try {
        \placeOrder($immediateOrCancel, 'btcusd', $side, \getAmount(), $range[$side]);
        $sleep = 1;
    } catch (ConnectionException $e) {
        echo "e[91m{$e->getMessage()}\e[0m\n";
        $sleep = 1;
    } catch (SystemException $e) {
        echo "e[91m{$e->getMessage()}\e[0m\n";
        $sleep = 10;
    } catch (InsufficientFundsException $e) {
        echo "e[91m{$e->getMessage()}\e[0m\n";
        $sleep = 1;
    } catch (\Exception $e) {
        \printException($e);
        exit(1);
    }
} while ($shutdown->isShutdownModeEnabled() === false);

echo "\nShutdown Detected\n";
exit(0);

