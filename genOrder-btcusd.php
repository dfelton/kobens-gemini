<?php
/**
 * Generates order on the btcusd trading pair
 */

$buy  = '0.00027000';
$save = '0.00000605';

$start  = '';
$end    = '';
$action = '';

$increment     =  '2.50';
$sellAfterGain =  '1.025'; // 1 = 0% change

require __DIR__.'/bootstrap-genorder.php';
(new GenOrder('btcusd'))->generate($buy, $save, $start, $end, $increment, $sellAfterGain, $action);

exit(0);
