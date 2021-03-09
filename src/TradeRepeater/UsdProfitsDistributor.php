<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Bucket;
use Kobens\Gemini\Command\Traits\KillFile;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Adapter;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;

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

    private TradeResource $tradeResource;

    private OrderStatusInterface $orderStatus;

    public function __construct(
        AddAmount $addAmount,
        Adapter $adapter,
        Bucket $bucket,
        Adapter $privateThrottlerAdapter,
        EmergencyShutdownInterface $emergencyShutdown,
        SleeperInterface $sleeper,
        TradeResource $tradeResource,
        OrderStatusInterface $orderStatus,
        $usdOrderSize = '0.0001'
    ) {
        $this->adapter = $adapter;
        $this->addAmount = $addAmount;
        $this->bucket = $bucket;
        $this->privateThrottlerAdapter = $privateThrottlerAdapter;
        $this->sleeper = $sleeper;
        $this->emergencyShutdown = $emergencyShutdown;
        $this->tradeResource = $tradeResource;
        $this->orderStatus = $orderStatus;
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
                    sprintf(
                        'Waiting for sufficient funds to add %s to %s buy order of %s @ %s %s/%s',
                        $pair->getMinOrderIncrement(),
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
            if ($isAvailableToAddTo) {
                try {
                    $amountAdded = $this->addAmount->add($trade->getId(), $pair->getMinOrderIncrement());
                    if (Compare::getResult($amountAdded, '0') === Compare::LEFT_GREATER_THAN) {
                        $this->bucket->removeFromBucket(
                            $pair->getQuote()->getSymbol(),
                            $this->getFundsRequiredForMinOrderIncrement($trade)
                        );
                    }
                    yield [
                        'type' => 'success',
                        'message' => sprintf(
                            "Increased %s buy order of %s by amount of %s @ %s %s/%s",
                            strtoupper($trade->getSymbol()),
                            $trade->getBuyAmount(),
                            $pair->getMinOrderIncrement(),
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
        }
    }

    private function getFundsRequiredForMinOrderIncrement(Trade $trade): string
    {
        $pair = Pair::getInstance($trade->getSymbol());
        $quoteAmount = Multiply::getResult($trade->getBuyPrice(), $pair->getMinOrderIncrement());
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
        $requiredAmount = $this->getFundsRequiredForMinOrderIncrement($trade);
        $bucketContains = $this->bucket->get(Pair::getInstance($trade->getSymbol())->getQuote()->getSymbol());

        return Compare::getResult($requiredAmount, $bucketContains) === Compare::LEFT_LESS_THAN;
    }

    private function getNext(): \Generator
    {
        // TODO: good spot to implement multiple strategies, where one strategy runs after another.
        // The act of iterating through all possible and incrementing only minimum amount possible
        // ensures we're "adding something to everything" but doesn't evenly distribute USD.
        // We could have strategies that take a round to "apply to better performing" or "apply to
        // ones with lowest USD investment", etc.

        // ^ keep that idea but thisis not the right spot... as it is not where we decide amount to be added.
        // Each strategy will need their own getNext() or something and strategies get invoked earlier on

        $lastId = 4873; // TODO: Start at 0 every time or log where it left off on last excution and resume from there?
        while (
            ! $this->emergencyShutdown->isShutdownModeEnabled() &&
            ! $this->killFileExists(self::KILL_FILE)
        ) {
            $trade = $this->fetch($lastId);
            if ($trade) {
                $lastId = $trade->getId();

                // TODO: Remove check on yfiusd once there is more "distribution strategies" implemented.
                //       Until then that pair just sucks up too much USD in relation to all the other pairs.
                $pair = Pair::getInstance($trade->getSymbol());
                if ($pair->getQuote()->getSymbol() === 'usd' && $pair->getSymbol() !== 'yfiusd') {
                    yield $trade;
                    $this->privateThrottlerAdapter->query('SELECT 1')->execute(); // ping database to keep connection alive
                }
            } else {
                $lastId = 0;
//                 $this->sleeper->sleep(
//                     60,
//                     function(): bool {
//                         return $this->emergencyShutdown->isShutdownModeEnabled();
//                     }
//                 );
            }
        }
    }

    private function fetch(int $lastId): ?Trade
    {
        $results = $this->adapter->query(
            'SELECT id FROM trade_repeater WHERE `id` > :id AND `status` = :status ORDER BY `id` LIMIT 1'
        )->execute(['id' => $lastId, 'status' => 'BUY_PLACED']);
        return $results->count() === 1 ? $this->tradeResource->getById((int) $results->current()['id']) : null;
    }
}
