<?php

namespace Kobens\Gemini\Exchange\Order;

use Kobens\Gemini\Exception\Exception;
use Kobens\Exchange\Order\StatusInterface;

/**
 * Class Status
 * @package Kobens\Gemini\Exchange\Order
 */
class Status implements StatusInterface
{
    /**
     * @param array $metaData
     * @return bool
     * @throws Exception
     */
    public function isCancelled(array $metaData): bool
    {
        if (!isset($metaData['is_cancelled'])) {
            throw new Exception('Metadata array missing "is_cancelled" information');
        }
        return $metaData['is_cancelled'] === true;
    }

    /**
     * @param array $metaData
     * @return bool
     * @throws Exception
     */
    public function isLive(array $metaData): bool
    {
        if (!isset($metaData['is_live'])) {
            throw new Exception('Metadata array missing "is_live" information');
        }
        return $metaData['is_live'] === true;
    }

    /**
     * @param array $metaData
     * @return bool
     * @throws Exception
     */
    public function isFilled(array $metaData): bool
    {
         if (!isset($metaData['is_live'], $metaData['remaining_amount'])) {
            throw new Exception('Metadata missing required keys.');
        }
        return $metaData['is_live'] === false && $metaData['remaining_amount'] === '0';
    }

}