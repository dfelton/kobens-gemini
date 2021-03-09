<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Command\Traits\KillFile;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Bucket;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Divide;
use Kobens\Math\BasicCalculator\Multiply;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Adapter;

// TODO: There is places we call this a "USD" distributor and there is places we don't assume USD but grab the quote dynamically. Which is it gonna be?
final class UsdProfitsDistributor implements UsdProfitsDistributorInterface
{
    use KillFile;

    private const KILL_FILE = 'kill_usd_profits_dist';

    private AddAmount $addAmount;

    private Adapter $adapter;

    private Adapter $privateThrottlerAdapter;

    private Bucket $bucket;

    private EmergencyShutdownInterface $emergencyShutdown;

    private SleeperInterface $sleeper;

    private OrderStatusInterface $orderStatus;

    /**
     * @var UsdProfitsDistributor\RecordSelectorInterface[]
     */
    private array $recordSelectors = [];

    private string $usdOrderSize;

    public function __construct(
        AddAmount $addAmount,
        Bucket $bucket,
        Adapter $privateThrottlerAdapter,
        EmergencyShutdownInterface $emergencyShutdown,
        SleeperInterface $sleeper,
        OrderStatusInterface $orderStatus,
        array $recordSelectors,
        string $usdOrderSize = '0.0005'
    ) {
        $this->addAmount = $addAmount;
        $this->bucket = $bucket;
        $this->privateThrottlerAdapter = $privateThrottlerAdapter;
        $this->sleeper = $sleeper;
        $this->emergencyShutdown = $emergencyShutdown;
        $this->orderStatus = $orderStatus;
        $this->usdOrderSize = $usdOrderSize;
        $this->init($recordSelectors);
    }

    /**
     * @param UsdProfitsDistributor\RecordSelectorInterface[] $recordSelectors
     */
    private function init(array $recordSelectors): void
    {
        if ($recordSelectors === []) {
            throw new \InvalidArgumentException(sprintf(
                '"$recordSelectors" must at least one instance as "%s" object.',
                UsdProfitsDistributor\RecordSelectorInterface::class
            ));
        }
        foreach ($recordSelectors as $selector) {
            if (!$selector instanceof UsdProfitsDistributor\RecordSelectorInterface) {
                throw new \InvalidArgumentException(sprintf(
                    '"$recordSelectors" must only contain instances of "%s"',
                    UsdProfitsDistributor\RecordSelectorInterface::class
                ));
            }
            $this->recordSelectors[] = $selector;
        }
    }

    public function execute(InputInterface $input, OutputInterface $output): \Generator
    {
        /** @var Trade $trade */
        foreach ($this->getNext() as $trade) {
            $isAvailableToAddTo = $this->isAvailableToAddTo($trade);
            if ($isAvailableToAddTo === false) {
                continue;
            }
            $pair = Pair::getInstance($trade->getSymbol());
            if (!$this->haveFundsForIncrement($trade)) {
                yield [
                    'type' => 'notice',
                    'message' => sprintf(
                        'Waiting for sufficient funds to add %s to %s buy order of %s @ %s %s/%s',
                        $this->getAddAmount($trade),
                        strtoupper($pair->getSymbol()),
                        $trade->getBuyAmount(),
                        $trade->getBuyPrice(),
                        strtoupper($pair->getBase()->getSymbol()),
                        strtoupper($pair->getQuote()->getSymbol())
                    )
                ];
                while (
                    ! $this->emergencyShutdown->isShutdownModeEnabled() &&
                    ! $this->killFileExists(self::KILL_FILE) &&
                    ! $this->haveFundsForIncrement($trade) &&
                    $isAvailableToAddTo == true
                ) {
                    $this->privateThrottlerAdapter->query('SELECT 1')->execute(); // ping database to keep connection alive
                    $this->sleeper->sleep(
                        60,
                        function (): bool {
                            return $this->killFileExists(self::KILL_FILE) || $this->emergencyShutdown->isShutdownModeEnabled();
                        }
                    );
                    $isAvailableToAddTo = $this->isAvailableToAddTo($trade);
                }
            }
            // Need to check again in case status changed while sleeping during a wait for funds
            if (
                $isAvailableToAddTo === true &&
                $this->emergencyShutdown->isShutdownModeEnabled() == false &&
                $this->killFileExists(self::KILL_FILE) === false
            ) {
                try {
                    $amountAdded = $this->addAmount->add($trade->getId(), $this->getAddAmount($trade));
                    if (Compare::getResult($amountAdded, '0') === Compare::LEFT_GREATER_THAN) {
                        $this->bucket->removeFromBucket(
                            $pair->getQuote()->getSymbol(),
                            $this->getFundsRequiredForOrderIncrement($trade)
                        );
                    }
                    yield [
                        'type' => 'success',
                        'message' => sprintf(
                            "Increased %s buy order of %s by amount of %s @ %s %s/%s",
                            strtoupper($trade->getSymbol()),
                            $trade->getBuyAmount(),
                            $this->getAddAmount($trade),
                            $trade->getBuyPrice(),
                            strtoupper($pair->getBase()->getSymbol()),
                            strtoupper($pair->getQuote()->getSymbol())
                        )
                    ];
                } catch (\Throwable $e) {
                    yield [
                        'type' => 'error',
                        'message' => $e->getMessage()
                    ];
                    throw $e;
                }
            }
            usleep(100000);
        }
    }

    private function getAddAmount(Trade $trade): string
    {
        $pair = Pair::getInstance($trade->getSymbol());
        $amount = Divide::getResult($this->usdOrderSize, $trade->getBuyPrice(), strlen(explode('.', $pair->getMinOrderIncrement())[1] ?? ''));
        if (Compare::getResult($amount, $pair->getMinOrderIncrement()) === Compare::LEFT_LESS_THAN) {
            $amount = $pair->getMinOrderIncrement();
        }
        return $amount;
    }

    private function getFundsRequiredForOrderIncrement(Trade $trade): string
    {
        $quoteAmount = Multiply::getResult($trade->getBuyPrice(), $this->getAddAmount($trade));
        $depositAmount = Multiply::getResult($quoteAmount, '0.0035'); // TODO: Reference constant or class or something...
        return Add::getResult($quoteAmount, $depositAmount);
    }

    private function isAvailableToAddTo(Trade $trade): bool
    {
        $order = $this->orderStatus->getStatus($trade->getBuyOrderId());
        $isAvailable = true;
        if (
            $order->is_live === false ||
            $order->executed_amount !== '0'
        ) {
            $isAvailable = false;
        }
        return $isAvailable;
    }

    private function haveFundsForIncrement(Trade $trade): bool
    {
        $requiredAmount = $this->getFundsRequiredForOrderIncrement($trade);
        $bucketContains = $this->bucket->get(Pair::getInstance($trade->getSymbol())->getQuote()->getSymbol());

        return Compare::getResult($requiredAmount, $bucketContains) === Compare::LEFT_LESS_THAN;
    }

    private function getNext(): \Generator
    {
        while (
            ! $this->emergencyShutdown->isShutdownModeEnabled() &&
            ! $this->killFileExists(self::KILL_FILE)
        ) {
            foreach ($this->recordSelectors as $selector) {
                foreach ($selector->get() as $trade) {
                    yield $trade;
                }
            }
        }
    }
}
