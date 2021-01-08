<?php
/**
 * Generates order on the linkusd trading pair
 */

$buy  = '0.500000';
$save = '0.010000';

$start  = '';
$end    = '';
$action = '';

$increment     =  '0.1';
$sellAfterGain =  '1.10'; // 1 = 0% change

require __DIR__.'/bootstrap-genorder.php';
(new GenOrder('linkusd'))->generate($buy, $save, $start, $end, $increment, $sellAfterGain, $action);

exit(0);
