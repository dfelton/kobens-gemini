<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater\FillMonitor;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrdersInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Exception\TradeRepeater\UnhealthyStateException;
use Kobens\Gemini\TradeRepeater\DataResource\AbstractDataResource;
use Kobens\Gemini\TradeRepeater\DataResource\BuyPlacedInterface;
use Kobens\Gemini\TradeRepeater\DataResource\SellPlacedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Rest extends Command
{
    protected static $defaultName = 'trade-repeater:fill-monitor-rest';

    /**
     * @var GetActiveOrdersInterface
     */
    private $activeOrders;

    /**
     * @var BuyPlacedInterface
     */
    private $buyPlaced;

    /**
     * @var SellPlacedInterface
     */
    private $sellPlaced;

    /**
     * @var EmergencyShutdownInterface
     */
    private $shutdown;

    /**
     * @var OrderStatusInterface
     */
    private $orderStatus;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        BuyPlacedInterface $buyPlacedInterface,
        SellPlacedInterface $sellPlacedInterface,
        GetActiveOrdersInterface $getActiveOrdersInterface,
        OrderStatusInterface $orderStatusInterface
    ) {
        $this->shutdown = $shutdownInterface;
        $this->buyPlaced = $buyPlacedInterface;
        $this->sellPlaced = $sellPlacedInterface;
        $this->activeOrders = $getActiveOrdersInterface;
        $this->orderStatus = $orderStatusInterface;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while ($this->shutdown->isShutdownModeEnabled() === false) {
            try {
                $this->mainLoop($output);
                \sleep(60);
            } catch (ConnectionException $e) {
                $output->writeln("<fg=red>{$this->now()}\tConnection Exception occurred.</>");
                \sleep(60);
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln("\n<fg=red>Shutdown Signal Detected</>\n");
    }

    /**
     * @param OutputInterface $output
     */
    private function mainLoop(OutputInterface $output): void
    {
        $activeIds = $this->getActiveOrderIds();
        foreach ($this->buyPlaced->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }
            if (   !\array_key_exists($row->buy_order_id, $activeIds['buy'])
                && $this->isStillHealthy($this->buyPlaced, $row->id)
                && $this->isFilled($row->buy_order_id)
                && $this->buyPlaced->setNextState($row->id)
            ) {
                $output->writeln("{$this->now()}\t({$row->id}) Buy order {$row->buy_order_id} on {$row->symbol} pair filled.");
            }
        }
        foreach ($this->sellPlaced->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }
            if (   !\array_key_exists($row->sell_order_id, $activeIds['sell'])
                && $this->isStillHealthy($this->sellPlaced, $row->id)
                && $this->isFilled($row->sell_order_id)
                && $this->sellPlaced->setNextState($row->id)
            ) {
                $output->writeln("{$this->now()}\t({$row->id}) Sell order {$row->sell_order_id} on {$row->symbol} pair filled.");
            }
        }
    }

    /**
     * Due to lag, it could have been picked up by the WebSocket FillMonitor.
     * By performing a redundant call to our db we can save ourselves a
     * curl request which is more important than the reduction of db calls
     * in order to preserve rate limits on exchange.
     *
     * @param AbstractDataResource $resource
     * @param $id
     * @return bool
     */
    private function isStillHealthy(AbstractDataResource $resource, int $id): bool
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
