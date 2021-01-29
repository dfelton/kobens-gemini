<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\FillMonitor;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrdersInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Command\Command\TradeRepeater\SleeperTrait;
use Kobens\Gemini\Exception\Api\Reason\InvalidNonceException;
use Kobens\Gemini\Exception\Api\Reason\MaintenanceException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\Exception\TradeRepeater\UnhealthyStateException;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\AbstractAction;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuyPlacedInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellPlacedInterface;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Exchange\PairInterface;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class Rest extends Command
{
    use SleeperTrait;

    private const EXCEPTION_DELAY = 60;

    protected static $defaultName = 'trade-repeater:fill-monitor-rest';

    private GetActiveOrdersInterface $activeOrders;

    private BuyPlacedInterface $buyPlaced;

    private SellPlacedInterface $sellPlaced;

    private EmergencyShutdownInterface $shutdown;

    private OrderStatusInterface $orderStatus;

    private SleeperInterface $sleeper;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        BuyPlacedInterface $buyPlacedInterface,
        SellPlacedInterface $sellPlacedInterface,
        GetActiveOrdersInterface $getActiveOrdersInterface,
        OrderStatusInterface $orderStatusInterface,
        SleeperInterface $sleeperInterface
    ) {
        $this->shutdown = $shutdownInterface;
        $this->buyPlaced = $buyPlacedInterface;
        $this->sellPlaced = $sellPlacedInterface;
        $this->activeOrders = $getActiveOrdersInterface;
        $this->orderStatus = $orderStatusInterface;
        $this->sleeper = $sleeperInterface;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('buy', 'b', InputOption::VALUE_OPTIONAL, 'Audit Buy Orders', '0');
        $this->addOption('sell', 's', InputOption::VALUE_OPTIONAL, 'Audit Sell Orders', '0');
        $this->addOption('pair', 'p', InputOption::VALUE_OPTIONAL, 'Pair to audit. Default to all');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $buyAudit = $input->getOption('buy') === '1';
        $sellAudit = $input->getOption('sell') === '1';
        $pair = $input->getOption('pair')
            ? Pair::getInstance($input->getOption('pair'))
            : null;

        while ($this->shutdown->isShutdownModeEnabled() === false) {
            $time = Subtract::getResult('0', (string) microtime(true));
            try {
                if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                    $output->writeln(sprintf(
                        "%s\tAUDIT START\t%s (%s)",
                        $this->now(),
                        ($pair === null) ? 'all pairs' : $pair->getSymbol(),
                        (
                            ($buyAudit ? '<fg=green>buy</>' : '') .
                            ($buyAudit && $sellAudit ? ' and ' : '') .
                            ($sellAudit ? '<fg=red>sell</>' : '')
                        )
                    ));
                }
                $this->mainLoop($output, $buyAudit, $sellAudit, $pair);
                $time = Add::getResult($time, (string) microtime(true));
                if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                    $output->writeln(sprintf(
                        "%s\tAUDIT END\t%s (%s) completed in %s seconds",
                        $this->now(),
                        ($pair === null) ? 'all pairs' : $pair->getSymbol(),
                        (
                            ($buyAudit ? '<fg=green>buy</>' : '') .
                            ($buyAudit && $sellAudit ? ' and ' : '') .
                            ($sellAudit ? '<fg=red>sell</>' : '')
                        ),
                        $time
                    ));
                }
                $this->sleep(3600 + (60 * rand(2, 5)), $this->sleeper, $this->shutdown);
            } catch (ConnectionException | MaintenanceException | SystemException $e) {
                $this->exceptionDelay($output, $e);
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln(sprintf(
            "<fg=red>%s\tShutdown signal detected - %s",
            $this->now(),
            self::class
        ));
        return 0;
    }

    private function exceptionDelay(OutputInterface $output, \Exception $e)
    {
        $output->writeln([
            "<fg=red>{$this->now()}\t{$e->getMessage()}</>",
            "<fg=red>{$this->now()}\tSleeping " . self::EXCEPTION_DELAY . " seconds</>"
        ]);
        $this->sleep(self::EXCEPTION_DELAY, $this->sleeper, $this->shutdown);
    }

    /**
     * @param OutputInterface $output
     */
    private function mainLoop(OutputInterface $output, bool $buyOrders = false, bool $sellOrders = false, PairInterface $pair = null): void
    {
        /** @var \Kobens\Gemini\TradeRepeater\Model\Trade $row */
        $activeIds = $this->getActiveOrderIds();
        if ($buyOrders) {
            $this->iterateBuyOrders($output, $activeIds, $pair);
        }
        if ($sellOrders) {
            $this->iterateSellOrders($output, $activeIds, $pair);
        }
    }

    private function iterateBuyOrders(OutputInterface $output, array $activeIds, PairInterface $pair = null): void
    {
        /** @var Trade $row */
        foreach ($this->buyPlaced->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }
            if (
                ($pair !== null && $pair->getSymbol() !== $row->getSymbol()) ||
                ($activeIds['buy'][$row->getBuyOrderId()] ?? null) ||
                $this->isStillHealthy($this->buyPlaced, $row->getId()) === false
            ) {
                continue;
            }

            $isFilled = null;
            $i = 0;
            while ($isFilled == null && $i <= 100) {
                try {
                    $isFilled = $this->isFilled($row->getBuyOrderId());
                } catch (InvalidNonceException $e) {
                    // shit happens. Have tickets to improve upon this
                    ++$i;
                }
            }

            if ($isFilled === null) {
                // Logging appropriate. bucking it for later. may be moot with other plans
                break;
            } elseif ($isFilled && $this->buyPlaced->setNextState($row->getId())) {
                $output->writeln(sprintf(
                    "%s\t(%d)\t<fg=green>BUY_FILLED</>\tOrder ID %d\t%s %s @ %s %s/%s",
                    $this->now(),
                    $row->getId(),
                    $row->getBuyOrderId(),
                    $row->getBuyAmount(),
                    strtoupper(Pair::getInstance($row->getSymbol())->getBase()->getSymbol()),
                    json_decode($row->getMeta())->buy_price,
                    strtoupper(Pair::getInstance($row->getSymbol())->getQuote()->getSymbol()),
                    strtoupper(Pair::getInstance($row->getSymbol())->getBase()->getSymbol()),
                ));
            }
        }
    }

    private function iterateSellOrders(OutputInterface $output, array $activeIds, PairInterface $pair = null): void
    {
        /** @var Trade $row */
        foreach ($this->sellPlaced->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            } elseif (
                ($pair !== null && $pair->getSymbol() !== $row->getSymbol()) ||
                ($activeIds['sell'][$row->getSellOrderId()] ?? null) ||
                $this->isStillHealthy($this->sellPlaced, $row->getId()) === false
            ) {
                continue;
            }

            $isFilled = null;
            $i = 0;
            while ($isFilled == null && $i <= 100) {
                try {
                    $isFilled = $this->isFilled($row->getSellOrderId());
                } catch (InvalidNonceException $e) {
                    // shit happens. Have tickets to improve upon this
                    ++$i;
                }
            }

            if ($isFilled === null) {
                // Logging appropriate. bucking it for later. may be moot with other plans
                break;
            } elseif ($isFilled && $this->sellPlaced->setNextState($row->getId())) {
                $output->writeln(sprintf(
                    "%s\t(%d)\t<fg=red>SELL_FILLED</>\tOrder ID %d\t%s %s @ %s %s/%s",
                    $this->now(),
                    $row->getId(),
                    $row->getSellOrderId(),
                    $row->getSellAmount(),
                    strtoupper(Pair::getInstance($row->getSymbol())->getBase()->getSymbol()),
                    json_decode($row->getMeta())->sell_price,
                    strtoupper(Pair::getInstance($row->getSymbol())->getQuote()->getSymbol()),
                    strtoupper(Pair::getInstance($row->getSymbol())->getBase()->getSymbol()),
                ));
            }
        }
    }

    /**
     * Due to lag, it could have been picked up by the WebSocket FillMonitor.
     * By performing a redundant call to our db we can save ourselves a
     * curl request which is more important than the reduction of db calls
     * in order to preserve rate limits on exchange.
     */
    private function isStillHealthy(AbstractAction $resource, int $id): bool
    {
        try {
            $resource->getHealthyRecord($id);
        } catch (UnhealthyStateException $e) {
            return false; // swallow exception
        }
        return true;
    }

    private function isFilled(int $orderId): bool
    {
        $order = $this->orderStatus->getStatus($orderId);
        return $order->executed_amount === $order->original_amount;
    }

    private function getActiveOrderIds(): array
    {
        $ids = ['buy' => [], 'sell' => []];
        foreach ($this->activeOrders->getOrders() as $order) {
            $ids[$order->side][$order->order_id] = null;
        }
        return $ids;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
