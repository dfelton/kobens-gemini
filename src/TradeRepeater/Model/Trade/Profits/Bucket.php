<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade\Profits;

use Kobens\Math\BasicCalculator\Add;
use Zend\Db\Adapter\Adapter;

final class Bucket
{
    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function addToBucket(string $currency, string $amount): void
    {
        $bucketAmount = $this->getForUpdate($currency);
        $total = Add::getResult($amount, $bucketAmount);
        $this->adapter->query(
            'INSERT INTO `repeater_profits_bucket` (currency, amount) VALUES (:currency, :amount) ON DUPLICATE KEY UPDATE amount = :amount',
            ['currency' => $currency, 'amount' => $total]
        );
    }

    public function getForUpdate(string $currency): string
    {
        $sql = 'SELECT `amount` FROM `repeater_profits_bucket` WHERE `currency` = :currency FOR UPDATE';
        $rows = $this->adapter->query($sql, ['currency' => $currency]);
        return $rows->count() === 1 ? $rows->current()->amount : '0';
    }
}
