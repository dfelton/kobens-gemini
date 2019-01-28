<?php
/**
 * Cancels all orders on exchange server
 */

require_once __DIR__.'/lib/cli.php';
require_once __DIR__.'/lib/cancelAll.php';
require_once __DIR__.'/lib/Zend/Debug.php';

if ((string) Cli::getArg('env') === 'production') {
    require_once __DIR__.'/env/production.php';
} else {
    require_once __DIR__.'/env/sandbox.php';
}

if ((bool) Cli::getArg('production')) {
    die("\n\tCannot cancel all orders in production.\n\n");
}

/** @var RestKey $restKey */
global $restKey;

$cancelAllOrders = new CancelAllOrders($restKey);
$cancelAllOrders->makeRequest();

$response = $cancelAllOrders->getResponse();
if (!is_string($response)) {
    throw new Exception('Unhandled Response');
}

$response = json_decode($response);

if ($response->result === 'ok') {
    echo "All orders cancelled.\n";
} else {
    echo "There was a problem cancelling all orders.\n";
    Zend_Debug::dump($response, 'response');
}
