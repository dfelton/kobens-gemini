<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\ForceMakerInterface;
use Kobens\Gemini\Exception\MaxIterationsException;
use Kobens\Gemini\Exception\Api\Reason\MaintenanceException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuyReadyInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuySentInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * TODO: Implement a pid file so this command cannot be ran more than process at a time.
 */
final class Buyer extends Command
{
    use SleeperTrait;

    private const EXCEPTION_DELAY = 60;

    protected static $defaultName = 'trade-repeater:buyer';

    private EmergencyShutdownInterface $shutdown;

    private BuyReadyInterface $buyReady;

    private BuySentInterface $buySent;

    private ForceMakerInterface $forceMaker;

    private SleeperInterface $sleeper;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        BuyReadyInterface $buyReadyInterface,
        BuySentInterface $buySentInterface,
        ForceMakerInterface $forceMakerInterface,
        SleeperInterface $sleeperInterface
    ) {
        $this->shutdown = $shutdownInterface;
        $this->buyReady = $buyReadyInterface;
        $this->buySent = $buySentInterface;
        $this->forceMaker = $forceMakerInterface;
        $this->sleeper = $sleeperInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Places buy orders on the exchange for the Gemini Trade Repeater');
        $this->addOption('delay', 'd', InputOption::VALUE_OPTIONAL, 'Delay in seconds to start looking again for new BUY_READY orders. Minimum 5 seconds', 5);
        $this->addOption('maxIterationsDelay', null, InputOption::VALUE_OPTIONAL, 'Delay in seconds to resume operations when a MaxIterationsException occurrs', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $delay = (int) $input->getOption('delay');
        if ($delay < 5) {
            $delay = 5;
        }
        $reportSleep = true;
        while ($this->shutdown->isShutdownModeEnabled() === false) {
            try {
                if (!$this->mainLoop($input, $output)) {
                    if ($reportSleep) {
                        $output->writeln("{$this->now()}\tBuy orders up to date. Sleeping...");
                        $reportSleep = false;
                    }
                    $this->sleep($delay, $this->sleeper, $this->shutdown);
                } else {
                    $reportSleep = true;
                }
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln("\n<fg=red>{$this->now()}\tShutdown signal detected.\n");
    }

    private function mainLoop(InputInterface $input, OutputInterface $output): bool
    {
        $placedOrders = false;
        /** @var Trade $row */
        foreach ($this->buyReady->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }

            $buyClientOrderId = 'repeater_'.$row->id.'_buy_'.\microtime(true);
            $this->buyReady->setNextState($row->id, $buyClientOrderId);

            if (true == $msg = $this->place($input, $output, $row, $buyClientOrderId)) {
                $this->buySent->setNextState($row->getId(), $msg->order_id, $msg->price);
                $output->writeln(\sprintf(
                    "%s\t(%d) Buy Order ID %s placed on %s pair for amount of %s at rate of %s",
                    $this->now(),
                    $row->getId(),
                    $msg->order_id,
                    $msg->symbol,
                    $msg->original_amount,
                    $msg->price
                ));
                if ($msg->price !== $row->buy_price) {
                    $output->writeln(\sprintf(
                        "%s\t\t<fg=yellow>(original buy price: %s)</>",
                        $this->now(), $row->buy_price
                    ));
                }
                $placedOrders = true;
            }
        }
        return $placedOrders;
    }

    private function place(InputInterface $input, OutputInterface $output, Trade $row, string $buyClientOrderId): ?\stdClass
    {
        $msg = null;
        try {
            $msg = $this->forceMaker->place(
                Pair::getInstance($row->getSymbol()),
                'buy',
                $row->getBuyAmount(),
                $row->getBuyPrice(), $buyClientOrderId
            );
        } catch (ConnectionException $e) {
            $this->buyReady->resetState($row->getId());
            $output->writeln("<fg=red>{$this->now()}\tConnection Exception Occurred.</>");

        } catch (MaintenanceException | SystemException $e) {
            $this->buyReady->resetState($row->getId());
            $output->writeln("<fg=red>{$this->now()}\t ({$row->symbol}) $e->getMessage()");
            $output->writeln("<fg=red>{$this->now()}\tSleeping ".self::EXCEPTION_DELAY." seconds...</>");
            $this->sleep(self::EXCEPTION_DELAY, $this->sleeper, $this->shutdown);

        } catch (MaxIterationsException $e) {
            $this->buyReady->resetState($row->getId());
            $output->writeln(\sprintf(
                "<fg=red>%s\tMax iterations reached for attempting ForceMaker on %s pair for price of %s.</>",
                $this->now(), $row->getSymbol(), $row->getBuyPrice()
            ));
            $output->writeln("<fg=red>{$this->now()}\tSleeping {$input->getOption('maxIterationsDelay')} seconds...</>");
            $this->sleep((int) $input->getOption('maxIterationsDelay'), $this->sleeper, $this->shutdown);

        } catch (\Exception $e) {
            $this->buySent->setErrorState($row->id, \get_class($e)."::{$e->getMessage()}");
            throw $e;
        }
        return $msg;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
