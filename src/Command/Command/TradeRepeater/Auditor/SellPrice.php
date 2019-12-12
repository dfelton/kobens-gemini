<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater\Auditor;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\BinaryCalculator\Compare;
use Kobens\Core\BinaryCalculator\Subtract;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelOrderInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\ForceMakerInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Exception\Api\Reason\MaintenanceException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellPlacedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\TableGateway\TableGateway;

/**
 * FIXME: This currently only works well for USD quoted trading pairs.
 * TODO:  Need to use a percentage based spread rather than fixed amount
 */
final class SellPrice extends Command
{
    private const EXCEPTION_DELAY = 60;

    private const MIN_AGE    = 1800;  // 30 minutes
    private const MIN_SPREAD = '200';

    protected static $defaultName = 'trade-repeater:audit:sell-price';

    /**
     * @var EmergencyShutdownInterface
     */
    private $shutdown;

    /**
     * @var SellPlacedInterface
     */
    private $sellPlaced;

    /**
     * @var GetPriceInterface
     */
    private $getPrice;

    /**
     * @var OrderStatusInterface
     */
    private $orderStatus;

    /**
     * @var CancelOrderInterface
     */
    private $cancelOrder;

    /**
     * @var ForceMakerInterface
     */
    private $forceMaker;

    /**
     * @var Subtract
     */
    private $bcsub;

    /**
     * @var Compare
     */
    private $bccomp;

    /**
     * @var \Zend\Db\Adapter\Driver\ConnectionInterface
     */
    private $connection;

    /**
     * @var TableGateway
     */
    private $table;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        SellPlacedInterface $sellPlacedInterface,
        GetPriceInterface $getPriceInterface,
        OrderStatusInterface $orderStatusInterface,
        CancelOrderInterface $cancelOrderInterface,
        ForceMakerInterface $forceMakerInterface,
        Subtract $bcsub,
        Compare $bccomp,
        \Zend\Db\Adapter\Adapter $adapter
    ) {
        $this->shutdown = $shutdownInterface;
        $this->sellPlaced = $sellPlacedInterface;
        $this->getPrice = $getPriceInterface;
        $this->orderStatus = $orderStatusInterface;
        $this->cancelOrder = $cancelOrderInterface;
        $this->forceMaker = $forceMakerInterface;
        $this->bcsub = $bcsub;
        $this->bccomp = $bccomp;
        $this->connection = $adapter->getDriver()->getConnection();
        $this->table = new TableGateway('trade_repeater', $adapter);
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
                $output->writeln("{$this->now()}\tRecords examined. Sleeping $sleep seconds.");
                \sleep($sleep);
            } catch (ConnectionException $e) {
                $this->exceptionDelay($output, $e);
            } catch (MaintenanceException $e) {
                $this->exceptionDelay($output, $e);
            } catch (SystemException $e) {
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
            "<fg=red>{$this->now()}\tSleeping ".self::EXCEPTION_DELAY." seconds</>"
        ]);
        \sleep(self::EXCEPTION_DELAY);
    }

    private function mainLoop(OutputInterface $output): void
    {
        $rows = $this->sellPlaced->getHealthyRecords();
        foreach ($rows as $row) {
            if ($this->shouldReset($row)) {
                $data = $this->cancelOrder->cancel($row->sell_order_id);

                $this->connection->beginTransaction();
                $row = $this->sellPlaced->getRecord($row->id, true);
                $meta = \json_decode($row->meta, true);

                if ($data->is_cancelled === true && $data->executed_amount === '0') {
                    $output->writeln("{$this->now()}\tResetting {$row->id}");
                    unset($meta['sell_price']);
                    $this->table->update(
                        [
                            'status' => 'BUY_FILLED',
                            'sell_order_id' => null,
                            'sell_client_order_id' => null,
                            'meta' => \json_encode($meta),
                        ],
                        ['id' => $row->id]
                    );
                } else {
                    $output->writeln("{$this->now()}\tERROR occurred with {$row->id}");
                    $meta['error_description'] = 'Unexpected result when cancelling order in '.self::class;
                    $meta['cancel_order_json'] = \json_encode($data);
                    $this->table->update(
                        [
                            'is_error' => 1,
                            'meta' => \json_encode($meta),
                        ],
                        ['id' => $row->id]
                    );
                }

                $this->connection->commit();
            }
        }
    }

    private function shouldReset(\ArrayObject $row): bool
    {
        $meta = \json_decode($row->meta);
        return $row->sell_price !== $meta->sell_price
            && \time() - \strtotime($row->updated_at) > self::MIN_AGE
            && $this->isSpreadOverThreshold($row->symbol, $meta->sell_price)
            && $this->orderStatus->getStatus($row->sell_order_id)->executed_amount === '0'
        ;
    }

    private function isSpreadOverThreshold(string $symbol, string $ask): bool
    {
        $difference = $this->bcsub->getResult($ask, $this->getPrice->getAsk($symbol));
        return $this->bccomp->getResult($difference, self::MIN_SPREAD) === Compare::LEFT_GREATER_THAN;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
