<?php

namespace Kobens\Gemini\Exchange\Order\Fee\Trade;

use Kobens\Math\BasicCalculator\Multiply;

final class BPS
{
    private $knownRates = [
        // API Rates
        '0.001',   // 0.100% (most common, so first to attempt)

        '0.0035',  // 0.350%
        '0.0025',  // 0.250%
        '0.002',   // 0.200%
        '0.0015',  // 0.150%
        '0.00125', // 0.125%
        '0.00075', // 0.075%
        '0.0005',  // 0.050%
        '0',       // 0.000%

        // Deprecated Rate(s)
        '0.01',    // 1.000%

        // Active Trader Rates (covered by API rates)

        // Web Fee Schedule is bullshit, never used before, keep it that way
        // Mobile Fee Schedule is also bullshit. Avoid like the plague
        // Market Fee Schedule - Don't even fucking consider charging for that; dicks for hinting it
    ];

    /**
     * @var BPS
     */
    private static $instance;

    private function __construct() { }

    public static function getInstance(): BPS
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $amount
     * @param string $feeAmount
     * @throws \LogicException
     * @return string
     */
    public function getRate(string $baseAmount, string $quoteRate, string $feeAmount): string
    {
        $quoteAmount = Multiply::getResult($baseAmount, $quoteRate);
        foreach ($this->knownRates as $rate) {
            $result = Multiply::getResult($quoteAmount, $rate);
            if ($result === $feeAmount) {
                return $rate;
            }
        }
        throw new \LogicException(\sprintf("Unhandled Fee. Amount '%s', Fee '%s'", $feeAmount, $feeAmount));
    }

}
