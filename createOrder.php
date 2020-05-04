<?php
/**
 * Logic for generating consistent order placement
 * points across a range of price points.
 */

require __DIR__.'/bootstrap.php';

use Kobens\Core\Db;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\PricePointGenerator;
use Zend\Db\TableGateway\TableGateway;

$buy  = '0.00016000';
$save = '0.00000300';

$start = '8000.00';
$end   = '9000.00';

$increment     =  '2.50';
$sellAfterGain =  '0.025';

$pair = Pair::getInstance('btcusd');
$result = PricePointGenerator::get(
    $pair,
    $buy,
    $start,
    $end,
    $increment,
    $sellAfterGain,
    $save
);
$table = new TableGateway('trade_repeater', Db::getAdapter());

foreach ($result->getPricePoints() as $position) {
    $table->insert([
        'is_enabled' => 1,
        'status' => 'BUY_READY',
        'symbol' => $pair->getSymbol(),
        'buy_amount' => $position->getBuyAmountBase(),
        'buy_price' => $position->getBuyPrice(),
        'sell_amount' => $position->getSellAmountBase(),
        'sell_price' => $position->getSellPrice(),
    ]);
}
