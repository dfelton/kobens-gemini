<?php

declare(strict_types=1);

namespace Kobens\Gemini\Exchange\Order\Fee;

final class MaxApiMakerBps
{
    /**
     * Highest API Maker Fee BPS
     *
     * @see https://gemini.com/fees/api-fee-schedule#api-fee
     */
    private const MAX_API_MAKER_BPS = '20';

    private function __construct()
    {
    }

    public static function get(): string
    {
        return self::MAX_API_MAKER_BPS;
    }
}
