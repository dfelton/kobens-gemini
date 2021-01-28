<?php

declare(strict_types=1);

namespace KobensTest\Gemini\Unit\TradeRepeater\Model\Trade\AddAmount;

use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount\BaseAfterFees;
use PHPUnit\Framework\TestCase;
use Kobens\Gemini\Exchange\Currency\Pair;

class BaseAfterFeesTest extends TestCase
{
    private BaseAfterFees $subject;

    /**
     * Pair: BTCUSD
     *
     * Rate: 10
     * Quote USD: 50
     * Hold Fee BPS: 35
     * Expected Base BTC Result: 4.98256103
     * Expected Quote USD Exact Amount: 49.8256103
     * Expected Quote USD Max Tier Maker Transaction Fee: .0498256103 (occurs at time of transaction, taken from deposit, remaining deposit returns to available funds)
     * Expected Quote USD Maker Fee Deposit: .17438963605 (USD held from available funds while order is on order books)
     * Remaining Quote USD: 0.00000006395
     *
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {
        $this->subject = new BaseAfterFees(Pair::getInstance('btcusd'), '50', '10');
    }

    public function testGetPairReturnsSameValuePassedToConstructor(): void
    {
        $this->assertSame($this->subject->getPair(), Pair::getInstance('btcusd'));
    }

    public function testGetRateReturnsSameValuePassedToConstructor(): void
    {
        $this->assertSame($this->subject->getRate(), '10');
    }

    public function testGetOriginalAmountReturnsSameValuePassedToConstructor(): void
    {
        $this->assertSame($this->subject->getOriginalQuoteAmount(), '50');
    }

    public function testBaseAmountIsCorrect(): void
    {
        $this->assertSame($this->subject->getBaseAmount(), '4.98256103');
    }

    public function testQuoteAmountIsCorrect(): void
    {
        $this->assertSame($this->subject->getQuoteAmount(), '49.8256103');
    }

    public function testQuoteMaxTierMakerTransactionFeeIsCorrect(): void
    {
        $this->assertSame($this->subject->getQuoteMaxTierMakerTransactionFee(), '0.0498256103');
    }

    public function testQuoteMakerFeeDepositIsCorrect(): void
    {
        $this->assertSame($this->subject->getQuoteMakerFeeDeposit(), '0.17438963605');
    }

    public function testQuoteRemainingIsCorrect(): void
    {
        $this->assertSame($this->subject->getQuoteRemaining(), '0.00000006395');
    }
}