<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\FillMonitor;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrdersInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Command\Command\TradeRepeater\SleeperTrait;
use Kobens\Gemini\Exception\Api\Reason\MaintenanceException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\Exception\TradeRepeater\UnhealthyStateException;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\AbstractAction;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuyPlacedInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellPlacedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while ($this->shutdown->isShutdownModeEnabled() === false) {
            try {
                $this->mainLoop($output);
                $this->sleep(60, $this->sleeper, $this->shutdown);
            } catch (ConnectionException | MaintenanceException | SystemException $e) {
                $this->exceptionDelay($output, $e);
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln("\n<fg=red>{$this->now()}\tShutdown signal detected.\n");
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
    private function mainLoop(OutputInterface $output): void
    {
        /** @var \Kobens\Gemini\TradeRepeater\Model\Trade $row */
        $activeIds = $this->getActiveOrderIds();
        foreach ($this->buyPlaced->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }
            if (
                !($activeIds['buy'][$row->getBuyOrderId()] ?? false) &&
                $this->isStillHealthy($this->buyPlaced, $row->getId()) &&
                $this->isFilled($row->getBuyOrderId()) &&
                $this->buyPlaced->setNextState($row->getId())
            ) {
                $output->writeln(sprintf(
                    "%s\t(%d) Buy order %d on %s pair filled.",
                    $this->now(),
                    $row->getId(),
                    $row->getBuyOrderId(),
                    $row->getSymbol()
                ));
            }
        }
        foreach ($this->sellPlaced->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }
            if (
                !($activeIds['sell'][$row->getSellOrderId()] ?? false) &&
                $this->isStillHealthy($this->sellPlaced, $row->getId()) &&
                $this->isFilled($row->getSellOrderId()) &&
                $this->sellPlaced->setNextState($row->getId())
            ) {
                $output->writeln(sprintf(
                    "%s\t(%d) Sell order %d on %s pair filled.",
                    $this->now(),
                    $row->getId(),
                    $row->getSellOrderId(),
                    $row->getSymbol()
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
