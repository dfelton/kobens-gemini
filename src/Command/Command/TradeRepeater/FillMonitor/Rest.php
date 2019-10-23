<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater\FillMonitor;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\Request\Order\Status\ActiveOrdersInterface;
use Kobens\Gemini\Api\Rest\Request\Order\Status\OrderStatus;
use Kobens\Gemini\Exception\TradeRepeater\UnhealthyStateException;
use Kobens\Gemini\TradeRepeater\DataResource\BuyPlacedInterface;
use Kobens\Gemini\TradeRepeater\DataResource\SellPlacedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Rest extends Command
{
    protected static $defaultName = 'trade-repeater:fill-monitor-rest';

    /**
     * Threshold in seconds to consider a non-active record old
     */
    private const OLD_RECORD_THRESHOLD = 30;

    /**
     * @var ActiveOrdersInterface
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

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        ActiveOrdersInterface $activeOrdersInterface,
        BuyPlacedInterface $buyPlacedInterface,
        SellPlacedInterface $sellPlacedInterface
    ) {
        parent::__construct();
        $this->activeOrders = $activeOrdersInterface;
        $this->buyPlaced = $buyPlacedInterface;
        $this->sellPlaced = $sellPlacedInterface;
        $this->shutdown = $shutdownInterface;
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
                $message = \json_encode([
                    'time' => $this->now(),
                    'message' => $e->getMessage(),
                    'class' => \get_class($e),
                    'trace' => $e->getTraceAsString(),
                ]);
                $this->shutdown->enableShutdownMode($message);
            }
        }
        $output->writeln("<fg=red>Shutdown Signal Detected</>");
    }

    private function mainLoop(OutputInterface $output): void
    {
        $activeIds = $this->getActiveOrderIds();
        foreach ($this->getOldBuyPlacedRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }
            try {
                $row = $this->buyPlaced->getHealthyRecord($row->id);
            } catch (UnhealthyStateException $e) {
                // Due to lag the Websocket FillMonitor may have beaten us to this record
                continue;
            }
            if (!\in_array($row, $activeIds) && $this->isFilled($row->buy_order_id)) {
                $output->writeln("{$this->now()}\t({$row->id}) Buy order {$row->buy_order_id} on {$row->symbol} pair filled.");
                $this->buyPlaced->setNextState($row->id);
            }
        }
        foreach ($this->getOldSellPlacedRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }
            try {
                $row = $this->buyPlaced->getHealthyRecord($row->id);
            } catch (UnhealthyStateException $e) {
                // Due to lag the Websocket FillMonitor may have beaten us to this record
                continue;
            }
            if (!\in_array($row, $activeIds) && $this->isFilled($row->sell_order_id)) {
                $output->writeln("{$this->now()}\t({$row->id}) Sell order {$row->sell_order_id} on {$row->symbol} pair filled.");
                $this->sellPlaced->setNextState($row->id);
            }
        }
    }

    private function isFilled(int $id): bool
    {
        $order = \json_decode((new OrderStatus($id))->getResponse()['body']);
        return $order->executed_amount === $order->original_amount;
    }

    private function getOldBuyPlacedRecords(): \Generator
    {
        foreach ($this->buyPlaced->getHealthyRecords() as $row) {
            if (\time() - \strtotime($row->updated_at) > self::OLD_RECORD_THRESHOLD) {
                yield $row;
            }
        }
    }

    private function getOldSellPlacedRecords(): \Generator
    {
        foreach ($this->sellPlaced->getHealthyRecords() as $row) {
            if (\time() - \strtotime($row->updated_at) > self::OLD_RECORD_THRESHOLD) {
                yield $row;
            }
        }
    }

    private function getActiveOrderIds(): array
    {
        $ids = [];
        foreach (\json_decode($this->activeOrders->getResponse()['body']) as $order) {
            $ids[] = $order->id;
        }
        return $ids;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
