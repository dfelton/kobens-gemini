<?php

namespace Kobens\Gemini\Exchange\Order;

use Kobens\Gemini\Exception\Exception;
use Kobens\Exchange\Order\StatusInterface;

class Status implements StatusInterface
{
    public function isCancelled(array $metaData): bool
    {
        if (!isset($metaData['is_cancelled'])) {
            throw new Exception('Metadata array missing "is_cancelled" information');
        }
        return $metaData['is_cancelled'] === true;
    }

    public function isLive(array $metaData): bool
    {
        if (!isset($metaData['is_live'])) {
            throw new Exception('Metadata array missing "is_live" information');
        }
        return $metaData['is_live'] === true;
    }

    public function isFilled(array $metaData): bool
    {
         if (!isset($metaData['is_live']) || !isset($metaData['remaining_amount'])) {
            throw new Exception('Metadata missing required keys.');
        }
        return $metaData['is_live'] === false && $metaData['remaining_amount'] === '0';
    }

}