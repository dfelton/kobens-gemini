<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\ForceMakerInterface;
use Kobens\Gemini\Exception\MaxIterationsException;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\DataResource\BuyFilledInterface;
use Kobens\Gemini\TradeRepeater\DataResource\SellSentInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * TODO: implement a pid file so this command cannot be ran more than once process at a time.
 */
final class Seller extends Command
{
    protected static $defaultName = 'trade-repeater:seller';

    /**
     * @var BuyFilledInterface
     */
    private $buyFilled;

    /**
     * @var SellSentInterface
     */
    private $sellSent;

    /**
     * @var EmergencyShutdownInterface
     */
    private $shutdown;

    /**
     * @var ForceMakerInterface
     */
    private $forceMaker;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        BuyFilledInterface $buyFilledInterface,
        SellSentInterface $sellSentInterface,
        ForceMakerInterface $forceMakerInterface
    ) {
        parent::__construct();
        $this->buyFilled = $buyFilledInterface;
        $this->sellSent = $sellSentInterface;
        $this->shutdown = $shutdownInterface;
        $this->forceMaker = $forceMakerInterface;
    }

    protected function configure()
    {
        $this->setDescription('Places sell orders on the exchange for the Gemini Trade Repeater');
        $this->addOption('delay', 'd', InputOption::VALUE_OPTIONAL, 'Delay in seconds to start looking again for new BUY_FILLED orders. Minimum 5 seconds.', 5);
        $this->addOption('maxIterationsDelay', null, InputOption::VALUE_OPTIONAL, 'Delay in seconds to resume operations when a MaxIterationsException occurrs', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $delay = (int) $input->getOption('delay');
        if ($delay < 5) {
            $delay = 5;
        }
        $reportSleep = true;
        while (!$this->shutdown->isShutdownModeEnabled()) {
            try {
                if (!$this->mainLoop($input, $output)) {
                    if ($reportSleep) {
                        $output->writeln("{$this->now()}\tSell orders up to date. Sleeping...");
                        $reportSleep = false;
                    }
                    \sleep($delay);
                } else {
                    $reportSleep = true;
                }
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln("<fg=red>Shutdown Signal Detected</>");
    }

    private function mainLoop(InputInterface $input, OutputInterface $output): bool
    {
        $placedOrders = false;
        foreach ($this->buyFilled->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }

            $sellClientOrderId = 'repeater_'.$row->id.'_sell_'.\microtime(true);
            $this->buyFilled->setNextState($row->id, $sellClientOrderId);

            if (true == $msg = $this->place($input, $output, $row, $sellClientOrderId)) {
                $this->sellSent->setNextState($row->id, $msg->order_id, $msg->price);
                $output->writeln(\sprintf(
                    "%s\t(%d) Sell Order ID %s placed on %s pair for amount of %s at rate of %s",
                    $this->now(), $row->id, $msg->order_id, $msg->symbol, $msg->original_amount, $msg->price
                ));
                if ($msg->price !== $row->sell_price) {
                    $output->writeln(\sprintf(
                        "%s\t\t<fg=yellow>(original sell price: %s)</>",
                        $this->now(), $row->sell_price
                    ));
                }
                $placedOrders;
            }
        }
        return $placedOrders;
    }

    private function place(InputInterface $input, OutputInterface $output, \ArrayObject $row, string $sellClientOrderId): ?\stdClass
    {
        $msg = null;
        try {
            $msg = $this->forceMaker->place(
                Pair::getInstance($row->symbol), 'sell', $row->sell_amount, $row->sell_price, $sellClientOrderId
            );
        } catch (ConnectionException $e) {
            $this->buyFilled->resetState($row->id);
            $output->writeln("<fg=red>{$this->now()}\tConnection Exception Occurred.</>");
        } catch (MaxIterationsException $e) {
            $this->buyFilled->resetState($row->id);
            $output->writeln(\sprintf(
                "<fg=red>%s\tMax iterations reached for attempting ForceMaker on %s pair for price of %s.</>",
                $this->now(), $row->symbol, $row->sell_price
            ));
            $output->writeln("<fg=red>{$this->now()}\tSleeping {$input->getOption('maxIterationsDelay')} seconds...</>");
            \sleep($input->getOption('maxIterationsDelay'));
        } catch (\Exception $e) {
            $this->sellSent->setErrorState($row->id, \get_class($e)."::{$e->getMessage()}");
            throw $e;
        }
        return $msg;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
