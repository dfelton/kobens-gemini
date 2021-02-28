<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade\Profits;

use Kobens\Math\BasicCalculator\Add;
use Zend\Db\Adapter\Adapter;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Subtract;
use Kobens\Core\Db;

final class Bucket
{
    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Returns amount remaining after adding to it.
     *
     * @param string $currency
     * @param string $amount
     */
    public function addToBucket(string $currency, string $amount): string
    {
        $useTransaction = false;
        if (Db::isInTransaction() === false) {
            $useTransaction = true;
            $this->adapter->getDriver()->getConnection()->beginTransaction();
        }
        $bucketAmount = $this->getForUpdate($currency);
        $total = Add::getResult($amount, $bucketAmount);
        try {
            $this->set($currency, $total);
            if ($useTransaction) {
                $this->adapter->getDriver()->getConnection()->commit();
            }
        } catch (\Throwable $e) {
            if ($useTransaction) {
                $this->adapter->getDriver()->getConnection()->rollback();
            }
            throw $e;
        }
        return $total;
    }

    public function get(string $currency): string
    {
        $sql = 'SELECT `amount` FROM `repeater_profits_bucket` WHERE `currency` = :currency';
        $rows = $this->adapter->query($sql, ['currency' => $currency]);
        return $rows->count() === 1 ? $rows->current()->amount : '0';
    }

    public function getForUpdate(string $currency): string
    {
        $sql = 'SELECT `amount` FROM `repeater_profits_bucket` WHERE `currency` = :currency FOR UPDATE';
        $rows = $this->adapter->query($sql, ['currency' => $currency]);
        return $rows->count() === 1 ? $rows->current()->amount : '0';
    }

    /**
     * Returns amount remaining after taking from it. Throws exception if
     * the requested amount is not available.
     *
     * @param string $currency
     * @param string $amount
     * @throws \InvalidArgumentException
     * @return string
     */
    public function removeFromBucket(string $currency, string $amount): string
    {
        $useTransaction = false;
        if (Db::isInTransaction() === false) {
            $useTransaction = true;
            $this->adapter->getDriver()->getConnection()->beginTransaction();
        }

        $bucketAmount = $this->getForUpdate($currency);
        if (Compare::getResult($bucketAmount, $amount) === Compare::RIGHT_GREATER_THAN) {
            if ($useTransaction) {
                $this->adapter->getDriver()->getConnection()->rollback();
            }
            throw new \InvalidArgumentException(sprintf(
                'Unable to remove amount of "%s" from "%s" bucket. Only "%s" remaining.',
                $amount,
                $currency,
                $bucketAmount
            ));
        }
        $remaining = Subtract::getResult($bucketAmount, $amount);
        try {
            $this->set($currency, $remaining);
            if ($useTransaction) {
                $this->adapter->getDriver()->getConnection()->commit();
            }
        } catch (\Throwable $e) {
            if ($useTransaction) {
                $this->adapter->getDriver()->getConnection()->rollback();
            }
            throw $e;
        }
        return $remaining;
    }

    private function set(string $currency, string $amount): void
    {
        $this->adapter->query(
            'INSERT INTO `repeater_profits_bucket` (currency, amount) VALUES (:currency, :amount) ON DUPLICATE KEY UPDATE amount = :amount',
            ['currency' => $currency, 'amount' => $amount]
        );
    }
}
