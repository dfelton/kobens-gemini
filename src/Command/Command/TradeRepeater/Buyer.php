<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\ForceMakerInterface;
use Kobens\Gemini\Command\Traits\KillFile;
use Kobens\Gemini\Exception\Api\Reason\MaintenanceException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\Exception\MaxIterationsException;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuyReadyInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuySentInterface;
use Kobens\Math\BasicCalculator\Compare;
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
    use KillFile;

    private const EXCEPTION_DELAY = 60;
    private const DEFAULT_DELAY = 2;
    private const KILL_FILE = 'kill_repeater_buyer';

    protected static $defaultName = 'repeater:buyer';

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

    protected function configure(): void
    {
        $this->setDescription('Places buy orders on the exchange for the Gemini Trade Repeater');
        $this->addOption('delay', 'd', InputOption::VALUE_OPTIONAL, 'Delay in seconds to start looking again for new BUY_READY orders. Minimum 2 seconds', self::DEFAULT_DELAY);
        $this->addOption('maxIterationsDelay', null, InputOption::VALUE_OPTIONAL, 'Delay in seconds to resume operations when a MaxIterationsException occurrs', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $delay = (int) $input->getOption('delay');
        if ($delay < self::DEFAULT_DELAY) {
            $delay = self::DEFAULT_DELAY;
        }
        while ($this->shutdown->isShutdownModeEnabled() === false && $this->killFileExists(self::KILL_FILE) === false) {
            try {
                if (!$this->mainLoop($input, $output)) {
                    $this->sleep($delay, $this->sleeper, $this->shutdown);
                }
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }
        if ($this->shutdown->isShutdownModeEnabled()) {
            $output->writeln(sprintf(
                "<fg=red>%s\tShutdown signal detected - %s",
                $this->now(),
                self::class
            ));
        }
        if ($this->killFileExists(self::KILL_FILE)) {
            $output->writeln(sprintf(
                "<fg=red>%s\tKill File Detected - %s",
                $this->now(),
                self::class
            ));
        }
        return 0;
    }

    private function mainLoop(InputInterface $input, OutputInterface $output): bool
    {
        $placedOrders = false;
        /** @var Trade $row */
        foreach ($this->buyReady->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }

            $buyClientOrderId = 'repeater_' . $row->getId() . '_buy_' . \microtime(true);
            $this->buyReady->setNextState($row->getId(), $buyClientOrderId);

            if (true == $msg = $this->place($input, $output, $row, $buyClientOrderId)) {
                $this->buySent->setNextState($row->getId(), $msg->order_id, $msg->price);
                $output->writeln(\sprintf(
                    "%s\t(%d)\t<fg=green>BUY</>_PLACED</>\tOrder ID %s\t%s %s @ %s %s/%s",
                    $this->now(),
                    $row->getId(),
                    $msg->order_id,
                    $msg->original_amount,
                    strtoupper(Pair::getInstance($msg->symbol)->getBase()->getSymbol()),
                    $msg->price,
                    strtoupper(Pair::getInstance($msg->symbol)->getBase()->getSymbol()),
                    strtoupper(Pair::getInstance($msg->symbol)->getQuote()->getSymbol()),
                ));
                if (Compare::getResult($msg->price, $row->getBuyPrice()) !== Compare::EQUAL) {
                    $output->writeln(\sprintf(
                        "%s\t\t<fg=yellow>(original buy price: %s)</>",
                        $this->now(),
                        $row->getBuyPrice()
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
                $row->getBuyPrice(),
                $buyClientOrderId
            );
        } catch (ConnectionException $e) {
            $this->buyReady->resetState($row->getId());
            $output->writeln("<fg=red>{$this->now()}\tConnection Exception Occurred.</>");
        } catch (MaintenanceException | SystemException $e) {
            $this->buyReady->resetState($row->getId());
            $output->writeln("<fg=red>{$this->now()}\t ({$row->symbol}) $e->getMessage()");
            $output->writeln("<fg=red>{$this->now()}\tSleeping " . self::EXCEPTION_DELAY . " seconds...</>");
            $this->sleep(self::EXCEPTION_DELAY, $this->sleeper, $this->shutdown);
        } catch (MaxIterationsException $e) {
            $this->buyReady->resetState($row->getId());
            $output->writeln(\sprintf(
                "<fg=red>%s\tMax iterations reached for attempting ForceMaker on %s pair for price of %s.</>",
                $this->now(),
                $row->getSymbol(),
                $row->getBuyPrice()
            ));
            $output->writeln("<fg=red>{$this->now()}\tSleeping {$input->getOption('maxIterationsDelay')} seconds...</>");
            $this->sleep((int) $input->getOption('maxIterationsDelay'), $this->sleeper, $this->shutdown);
        } catch (\Exception $e) {
            $this->buySent->setErrorState($row->getId(), \get_class($e) . "::{$e->getMessage()}");
            throw $e;
        }
        return $msg;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
