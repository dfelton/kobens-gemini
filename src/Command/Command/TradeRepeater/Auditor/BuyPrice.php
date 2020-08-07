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
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuyPlacedInterface;
use Kobens\Math\PercentDifference;
use Kobens\Math\BasicCalculator\Compare;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Driver\ConnectionInterface;
use Zend\Db\TableGateway\TableGateway;

final class BuyPrice extends Command
{
    use SleeperTrait;

    private const EXCEPTION_DELAY = 60;

    private const MIN_AGE    = 1800;  // 30 minutes
    private const MIN_SPREAD = '2';

    protected static $defaultName = 'trade-repeater:audit:buy-price';

    private EmergencyShutdownInterface $shutdown;

    private BuyPlacedInterface $buyPlaced;

    private GetPriceInterface $getPrice;

    private OrderStatusInterface $orderStatus;

    private CancelOrderInterface $cancelOrder;

    private ConnectionInterface $connection;

    private TableGateway $table;

    private SleeperInterface $sleeper;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        BuyPlacedInterface $buyPlacedInterface,
        GetPriceInterface $getPriceInterface,
        OrderStatusInterface $orderStatusInterface,
        CancelOrderInterface $cancelOrderInterface,
        \Zend\Db\Adapter\Adapter $adapter,
        SleeperInterface $sleeperInterface
    ) {
        $this->shutdown = $shutdownInterface;
        $this->buyPlaced = $buyPlacedInterface;
        $this->getPrice = $getPriceInterface;
        $this->orderStatus = $orderStatusInterface;
        $this->cancelOrder = $cancelOrderInterface;
        $this->connection = $adapter->getDriver()->getConnection();
        $this->table = new TableGateway('trade_repeater', $adapter);
        $this->sleeper = $sleeperInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->addOption('delay', 'd', InputOption::VALUE_OPTIONAL, 'Time in seconds between searching for records.', 600);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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

    private function mainLoop(OutputInterface $output): void
    {
        foreach ($this->buyPlaced->getHealthyRecords() as $row) {
            if ($this->shouldReset($row)) {
                $this->resetTrade($row, $output);
            }
        }
    }

    private function resetTrade(Trade $row, OutputInterface $output): void
    {
        $data = $this->cancelOrder->cancel($row->getBuyOrderId());
        $this->connection->beginTransaction();
        $row = $this->buyPlaced->getRecord($row->getId(), true);

        if ($data->is_cancelled === true && $data->executed_amount === '0') {
            $output->writeln("{$this->now()}\tResetting {$row->getId()}");
            $this->table->update(
                [
                    'status' => 'BUY_READY',
                    'buy_order_id' => null,
                    'buy_client_order_id' => null,
                    'meta' => null,
                ],
                ['id' => $row->getId()]
            );
        } else {
            $output->writeln("{$this->now()}\tERROR occurred with {$row->getId()}");
            $meta = \json_decode($row->meta, true);
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
        return $row->getBuyPrice() !== $meta->buy_price
            && \time() - \strtotime($row->getUpdatedAt()) > self::MIN_AGE
            && $this->isSpreadOverThreshold($row->getSymbol(), $meta->buy_price)
            && $this->orderStatus->getStatus($row->getBuyOrderId())->executed_amount === '0'
        ;
    }

    private function isSpreadOverThreshold(string $symbol, string $bid): bool
    {
        $difference = PercentDifference::getResult($bid, $this->getPrice->getBid($symbol));
        return Compare::getResult($difference, self::MIN_SPREAD) === Compare::LEFT_GREATER_THAN;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
