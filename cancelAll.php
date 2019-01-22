<?php
/**
 * Creates orders
 */

require_once 'lib/cli.php';
require_once ((bool) Cli::getArg('production')) ? 'env/production.php' : 'env/sandbox.php';
require_once 'lib/cancelAll.php';

/** @var RestKey $restKey */
global $restKey;

$cancelAllOrders = new CancelAllOrders($restKey);
$cancelAllOrders->makeRequest();

$response = $cancelAllOrders->getResponse();
if (!is_string($response)) {
    throw new Exception('Unhandled Response');
}

$response = json_decode($response, true);

if ($response['result'] === 'ok') {
    echo "All Orders Cancelled.\n";
} else {
    echo "There was a problem cancelling all orders.\n";
    Zend_Debug::dump($response, 'response');
}

Zend_Debug::dump($cancelAllOrders->getResponse());