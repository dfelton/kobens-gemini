<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater\Auditor;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotationalVolumeInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelAllSessionOrdersInterface;
use Kobens\Gemini\Command\Command\TradeRepeater\SleeperTrait;
use Kobens\Gemini\Exception\Api\Reason\InvalidNonceException;
use Kobens\Gemini\Exception\Api\Reason\MaintenanceException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\TradeRepeater\Model\MaxBPSInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellPlacedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Audits the current BPS for Maker orders placed via API.
 * If the BPS is ever detected to be over the maximum threshold expected for
 * trade repeater, this class will initiate an emergency shutdown event so
 * all trading activity is halted. Furthermore, it will use the CancelAllSessionOrdersInterface
 * to cancel all currently open sell orders placed by the TradeRepeater's Seller class.
 */
final class BPS extends Command
{
    use SleeperTrait;

    private const SLEEP_DELAY = 600;
    private const SLEEP_EXCEPTION_DELAY = 10;

    protected static $defaultName = 'trade-repeater:audit:bps';

    /**
     * @var EmergencyShutdownInterface
     */
    private $shutdown;

    /**
     * @var GetNotationalVolumeInterface
     */
    private $volume;

    /**
     * @var MaxBPSInterface
     */
    private $maxBPS;

    /**
     * @var SellPlacedInterface
     */
    private $sellPlaced;

    /**
     * @var CancelAllSessionOrdersInterface
     */
    private $cancel;

    /**
     * @var SleeperInterface
     */
    private $sleeper;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        GetNotationalVolumeInterface $getNotationalVolumeInterface,
        MaxBPSInterface $maxBPSInterface,
        SellPlacedInterface $sellPlacedInterface,
        CancelAllSessionOrdersInterface $cancelAllSessionOrdersInterface,
        SleeperInterface $sleeperInterface
    ) {
        $this->shutdown = $shutdownInterface;
        $this->volume = $getNotationalVolumeInterface;
        $this->maxBPS = $maxBPSInterface;
        $this->sellPlaced = $sellPlacedInterface;
        $this->cancel = $cancelAllSessionOrdersInterface;
        $this->sleeper = $sleeperInterface;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sleep = 0;
        while (!$this->shutdown->isShutdownModeEnabled()) {
            $this->sleep($sleep, $this->sleeper, $this->shutdown);
            try {
                $currentBPS = $this->volume->getVolume()->api_maker_fee_bps;
                if ($currentBPS <= $this->maxBPS->getMaxBPS()) {
                    $output->writeln("{$this->now()}\tBPS verified to be at or under threshold: <fg=green>$currentBPS</>");
                    $sleep = self::SLEEP_DELAY;
                } else {
                    $this->shutdown->enableShutdownMode(
                        new \Exception("BPS Threshold Detected ($currentBPS BPS")
                    );
                    $output->writeln([
                        "<fg=red>{$this->now()}\tWARNING: BPS Detected to be over threshold. Current BPS: $currentBPS</>",
                        "<fg=red>{$this->now()}\tInitiating cancellation of all trade repeater sell orders</>"
                    ]);
                    // TODO: We should sleep the max allowed time for our curl calls in case Seller is in the middle of placing an order.
                    $this->cancelSellOrders();
                }
            } catch (ConnectionException $e) {
                $output->writeln("<fg=red>{$this->now()}\t{$e->getMessage()}</>");
                $sleep = self::SLEEP_DELAY;
            } catch (MaintenanceException $e) {
                $output->writeln("<fg=red>{$this->now()}\t{$e->getMessage()}</>");
                $sleep = self::SLEEP_DELAY;
            } catch (SystemException $e) {
                $output->writeln("<fg=red>{$this->now()}\t{$e->getMessage()}</>");
                $sleep = self::SLEEP_DELAY;
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }

        $output->writeln("\n<fg=red>{$this->now()}\tShutdown signal detected.\n");
    }

    private function cancelSellOrders(): array
    {
        $msg = null;
        $i = 0;
        do {
            ++$i;
            try {
                $msg = $this->cancel->cancelSessionOrders();
            } catch (InvalidNonceException $e) {
                // swallow exception. Could have clashed with Seller command on a last attempted order.
            } catch (ConnectionException $e) {
                // swallow exception
            } catch (MaintenanceException $e) {
                // now what the fuck?
            } catch (SystemException $e) {

            }
        } while ($msg = null && $i < 100);
        foreach ($msg->details->cancelledOrders as $orderId) {
        }

    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
