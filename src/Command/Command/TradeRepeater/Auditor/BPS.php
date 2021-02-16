<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\Auditor;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolumeInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelAllSessionOrdersInterface;
use Kobens\Gemini\Command\Command\TradeRepeater\SleeperTrait;
use Kobens\Gemini\Exception\Api\Reason\InvalidNonceException;
use Kobens\Gemini\Exception\Api\Reason\MaintenanceException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\Exchange\Order\Fee\MaxApiMakerBps;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellPlacedInterface;
use Kobens\Math\BasicCalculator\Compare;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Audits the current BPS for Maker orders placed via API.
 *
 * If the BPS is ever detected to be over the maximum threshold expected
 * for trade repeater, this class will initiate an emergency shutdown
 * event so all trading activity is halted. Furthermore, it will use the
 * CancelAllSessionOrdersInterface to cancel all currently open sell orders
 * placed by the TradeRepeater's Seller class.
 */
final class BPS extends Command
{
    use SleeperTrait;

    private const SLEEP_DELAY = 600;
    private const SLEEP_EXCEPTION_DELAY = 10;

    protected static $defaultName = 'repeater:audit:bps';

    private EmergencyShutdownInterface $shutdown;

    private GetNotionalVolumeInterface $volume;

    private SellPlacedInterface $sellPlaced;

    private CancelAllSessionOrdersInterface $cancel;

    private SleeperInterface $sleeper;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        GetNotionalVolumeInterface $getNotationalVolumeInterface,
        SellPlacedInterface $sellPlacedInterface,
        CancelAllSessionOrdersInterface $cancelAllSessionOrdersInterface,
        SleeperInterface $sleeperInterface
    ) {
        $this->shutdown = $shutdownInterface;
        $this->volume = $getNotationalVolumeInterface;
        $this->sellPlaced = $sellPlacedInterface;
        $this->cancel = $cancelAllSessionOrdersInterface;
        $this->sleeper = $sleeperInterface;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        while (!$this->shutdown->isShutdownModeEnabled()) {
            try {
                $this->auditBPS($output);
            } catch (ConnectionException | MaintenanceException | SystemException $e) {
                $output->writeln("<fg=red>{$this->now()}\t{$e->getMessage()}</>");
                $this->sleep(self::SLEEP_DELAY, $this->sleeper, $this->shutdown);
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
                $exitCode = 1;
            }
        }
        $output->writeln("\n<fg=red>{$this->now()}\tShutdown signal detected.\n");
        return $exitCode;
    }

    /**
     * Checks the current maker fee bps for API orders and if it is determined
     * to be over the allowed threshold for Trade Repeater orders, an emergency
     * shutdown is enabled and all session sell orders are cancelled so that
     * they can be manually assessed and re-opened with an appropriate sell price.
     *
     * TODO: Someday auto-calculate new sell price. Feasible we eventually have the volume
     * necessary to have a lower maker BPS fee, and feasible that instead of simply accepting
     * more earnings, we want to trade on smaller price swings. However should our volume
     * lose the lower fee tier, we'd want to cancel and re-adjust to higher margin for
     * sell price.
     * TODO: Describe the above todo (lol todo on a todo) better in a GitHub issue.
     *
     * @param OutputInterface $output
     */
    private function auditBPS(OutputInterface $output): void
    {
        $currentBPS = $this->volume->getVolume()->api_maker_fee_bps;
        if (Compare::getResult((string) $currentBPS, MaxApiMakerBps::get()) === Compare::LEFT_GREATER_THAN) {
            $output->writeln("{$this->now()}\tBPS verified to be at or under threshold: <fg=green>$currentBPS</>");
            $this->sleep(self::SLEEP_DELAY, $this->sleeper, $this->shutdown);
        } else {
            $this->shutdown->enableShutdownMode(
                new \Exception("BPS Threshold Detected ($currentBPS BPS")
            );
            $output->writeln([
                "<fg=red>{$this->now()}\tWARNING: BPS Detected to be over threshold. Current BPS: $currentBPS</>",
                "<fg=red>{$this->now()}\tInitiating cancellation of all trade repeater sell orders</>"
            ]);
            // TODO: Cheap way of logging order IDs to file. Should probably write these elsewhere.
            $this->shutdown->enableShutdownMode(
                new \Exception(\sprintf(
                    'Trade Repeater Order IDs cancelled: %s',
                    implode(',', $this->cancelSellOrders())
                ))
            );
        }
    }

    /**
     * @param OutputInterface $output
     * @return array
     */
    private function cancelSellOrders(OutputInterface $output): array
    {
        $msg = null;
        do {
            try {
                $msg = $this->cancel->cancelSessionOrders();
            } catch (InvalidNonceException | ConnectionException | MaintenanceException | SystemException $e) {
                $output->writeln("<fg=red>{$this->now()}\tException: {$e->getMessage()}");
                $output->writeln(\sprintf(
                    "<fg=red>%s\tSleeping %d second(s) before attempting again to cancel all session orders.</>",
                    $this->now(),
                    self::SLEEP_EXCEPTION_DELAY
                ));
                $this->sleep(self::SLEEP_EXCEPTION_DELAY, $this->sleeper, $this->shutdown);
            } catch (\Exception $e) {
                $output->writeln(\sprintf(
                    "<fg=red>%s\tERROR: TRADE REPEATER SESSION ORDERS LEFT OPEN.</>",
                    $this->now(),
                ));
            }
        } while ($msg = null);
        $orderIds = [];
        foreach ($msg->details->cancelledOrders as $orderId) {
            $orderIds[] = $orderId;
        }
        return $orderIds;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
