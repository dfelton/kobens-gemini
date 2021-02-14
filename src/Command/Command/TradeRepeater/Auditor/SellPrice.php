<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\Auditor;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelOrderInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Command\Command\TradeRepeater\SleeperTrait;
use Kobens\Gemini\Exception\Api\Reason\MaintenanceException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellPlacedInterface;
use Kobens\Math\PercentDifference;
use Kobens\Math\BasicCalculator\Compare;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Driver\ConnectionInterface;
use Zend\Db\TableGateway\TableGateway;

final class SellPrice extends Command
{
    use SleeperTrait;

    private const EXCEPTION_DELAY = 60;

    private const MIN_AGE    = 1800;  // 30 minutes
    private const MIN_SPREAD = '2';   // Percentage

    protected static $defaultName = 'repeater:audit:sell-price';

    private EmergencyShutdownInterface $shutdown;

    private SellPlacedInterface $sellPlaced;

    private GetPriceInterface $getPrice;

    private OrderStatusInterface $orderStatus;

    private CancelOrderInterface $cancelOrder;

    private ConnectionInterface $connection;

    private TableGateway $table;

    private SleeperInterface $sleeper;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        SellPlacedInterface $sellPlacedInterface,
        GetPriceInterface $getPriceInterface,
        OrderStatusInterface $orderStatusInterface,
        CancelOrderInterface $cancelOrderInterface,
        \Zend\Db\Adapter\Adapter $adapter,
        SleeperInterface $sleeperInterface
    ) {
        $this->shutdown = $shutdownInterface;
        $this->sellPlaced = $sellPlacedInterface;
        $this->getPrice = $getPriceInterface;
        $this->orderStatus = $orderStatusInterface;
        $this->cancelOrder = $cancelOrderInterface;
        $this->connection = $adapter->getDriver()->getConnection();
        $this->table = new TableGateway('trade_repeater', $adapter);
        $this->sleeper = $sleeperInterface;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('delay', 'd', InputOption::VALUE_OPTIONAL, 'Time in seconds between searching for records.', 600);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sleep = (int) $input->getOption('delay');
        if ($sleep < 10) {
            $sleep = 10;
        }

        while (!$this->shutdown->isShutdownModeEnabled()) {
            try {
                $this->mainLoop($output);
                if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                    $output->writeln("{$this->now()}\tRecords examined. Sleeping $sleep seconds.");
                }
                $this->sleep($sleep, $this->sleeper, $this->shutdown);
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

    private function mainLoop(OutputInterface $output): void
    {
        /** @var Trade $row */
        foreach ($this->sellPlaced->getHealthyRecords() as $row) {
            if ($this->shouldReset($row)) {
                $this->resetTrade($row, $output);
            }
        }
    }

    private function resetTrade(Trade $row, OutputInterface $output): void
    {
        $data = $this->cancelOrder->cancel($row->getSellOrderId());
        $this->connection->beginTransaction();
        $row = $this->sellPlaced->getRecord($row->getId(), true);
        $meta = \json_decode($row->getMeta(), true);

        if ($data->is_cancelled === true && $data->executed_amount === '0') {
            $output->writeln("{$this->now()}\tResetting {$row->getId()}");
            unset($meta['sell_price']);
            $this->table->update(
                [
                    'status' => 'BUY_FILLED',
                    'sell_order_id' => null,
                    'sell_client_order_id' => null,
                    'meta' => \json_encode($meta),
                ],
                ['id' => $row->getId()]
            );
        } else {
            $output->writeln("{$this->now()}\tERROR occurred with {$row->getId()}");
            $meta['error_description'] = 'Unexpected result when cancelling order in ' . self::class;
            $meta['cancel_order_json'] = \json_encode($data);
            $this->table->update(
                [
                    'is_error' => 1,
                    'meta' => \json_encode($meta),
                ],
                ['id' => $row->getId()]
            );
        }
        $this->connection->commit();
    }

    private function shouldReset(Trade $row): bool
    {
        $meta = \json_decode($row->getMeta());
        return $row->getSellPrice() !== $meta->sell_price
            && \time() - \strtotime($row->getUpdatedAt()) > self::MIN_AGE
            && $this->isSpreadOverThreshold($row->getSymbol(), $meta->sell_price)
            && $this->orderStatus->getStatus($row->getSellOrderId())->executed_amount === '0'
        ;
    }

    private function isSpreadOverThreshold(string $symbol, string $ask): bool
    {
        $difference = PercentDifference::getResult($this->getPrice->getAsk($symbol), $ask);
        return Compare::getResult($difference, self::MIN_SPREAD) === Compare::LEFT_GREATER_THAN;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
