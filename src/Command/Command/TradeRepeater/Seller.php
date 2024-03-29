<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\ForceMakerInterface;
use Kobens\Gemini\Command\Traits\GetIntArg;
use Kobens\Gemini\Command\Traits\GetNow;
use Kobens\Gemini\Command\Traits\KillFile;
use Kobens\Gemini\Command\Traits\Output;
use Kobens\Gemini\Command\Traits\TradeRepeater\DbPing;
use Kobens\Gemini\Command\Traits\TradeRepeater\ExitProgram;
use Kobens\Gemini\Command\Traits\TradeRepeater\SleeperTrait;
use Kobens\Gemini\Exception\MaxIterationsException;
use Kobens\Gemini\Exception\Api\Reason\MaintenanceException;
use Kobens\Gemini\Exception\Api\Reason\SystemException;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuyFilledInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellSentInterface;
use Kobens\Math\BasicCalculator\Compare;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Adapter;

/**
 * TODO: implement a pid file so this command cannot be ran more than once process at a time.
 */
final class Seller extends Command
{
    use ExitProgram;
    use SleeperTrait;
    use KillFile;
    use GetIntArg;
    use GetNow;
    use DbPing;
    use Output;

    private const EXCEPTION_DELAY = 60;
    private const KILL_FILE = 'kill_repeater_seller';

    protected static $defaultName = 'repeater:seller';

    private BuyFilledInterface $buyFilled;

    private SellSentInterface $sellSent;

    private EmergencyShutdownInterface $shutdown;

    private ForceMakerInterface $forceMaker;

    private SleeperInterface $sleeper;

    private Adapter $privateThrottlerAdapter;

    private Adapter $publicThrottlerAdapter;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        BuyFilledInterface $buyFilledInterface,
        SellSentInterface $sellSentInterface,
        ForceMakerInterface $forceMakerInterface,
        SleeperInterface $sleeperInterface,
        Adapter $privateThrottlerAdapter,
        Adapter $publicThrottlerAdapter
    ) {
        $this->buyFilled = $buyFilledInterface;
        $this->sellSent = $sellSentInterface;
        $this->shutdown = $shutdownInterface;
        $this->forceMaker = $forceMakerInterface;
        $this->sleeper = $sleeperInterface;
        $this->privateThrottlerAdapter = $privateThrottlerAdapter;
        $this->publicThrottlerAdapter = $publicThrottlerAdapter;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Places sell orders on the exchange for the Gemini Trade Repeater');
        $this->addOption('delay', 'd', InputOption::VALUE_OPTIONAL, 'Delay in seconds to start looking again for new BUY_FILLED orders. Minimum 5 seconds.', 5);
        $this->addOption('maxIterationsDelay', null, InputOption::VALUE_OPTIONAL, 'Delay in seconds to resume operations when a MaxIterationsException occurrs', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $delay = $this->getIntArg($input, 'delay', 2, 1, 60);
        while ($this->shutdown->isShutdownModeEnabled() === false && $this->killFileExists(self::KILL_FILE) === false) {
            try {
                if (!$this->mainLoop($input, $output)) {
                    $this->ping($this->privateThrottlerAdapter);
                    $this->ping($this->publicThrottlerAdapter);
                    $this->sleep($delay, $this->sleeper, $this->shutdown);
                }
            } catch (\Throwable $e) {
                $this->shutdown->enableShutdownMode($e);
                $exitCode = 1;
            }
        }
        $this->outputExit($output, $this->shutdown, self::KILL_FILE);
        return $exitCode;
    }

    private function mainLoop(InputInterface $input, OutputInterface $output): bool
    {
        $placedOrders = false;
        /** @var Trade $row */
        foreach ($this->buyFilled->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }

            $sellClientOrderId = 'repeater_' . $row->getId() . '_sell_' . microtime(true);
            $this->buyFilled->setNextState($row->getId(), $sellClientOrderId);

            if (true == $msg = $this->place($input, $output, $row, $sellClientOrderId)) {
                $this->sellSent->setNextState($row->getId(), $msg->order_id, $msg->price);
                $output->writeln(sprintf(
                    "%s\t(%d)\t<fg=red>SELL</>_PLACED\tOrder ID %s\t%s %s @ %s %s/%s",
                    $this->getNow(),
                    $row->getId(),
                    $msg->order_id,
                    $msg->original_amount,
                    strtoupper(Pair::getInstance($msg->symbol)->getBase()->getSymbol()),
                    $msg->price,
                    strtoupper(Pair::getInstance($msg->symbol)->getBase()->getSymbol()),
                    strtoupper(Pair::getInstance($msg->symbol)->getQuote()->getSymbol()),
                ));
                if (Compare::getResult($msg->price, $row->getSellPrice()) !== Compare::EQUAL) {
                    $output->writeln(sprintf(
                        "%s\t\t<fg=yellow>(original sell price: %s)</>",
                        $this->getNow(),
                        $row->getSellPrice()
                    ));
                }
                $placedOrders;
            }
        }
        return $placedOrders;
    }

    /**
     * Place a sell order on the order book
     *
     * TODO Handle MarketNotOpenException
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Trade $row
     * @param string $sellClientOrderId
     * @throws \Exception
     * @return \stdClass|NULL
     */
    private function place(InputInterface $input, OutputInterface $output, Trade $row, string $sellClientOrderId): ?\stdClass
    {
        $msg = null;
        try {
            $msg = $this->forceMaker->place(
                Pair::getInstance($row->getSymbol()),
                'sell',
                $row->getSellAmount(),
                $row->getSellPrice(),
                $sellClientOrderId
            );
        } catch (ConnectionException $e) {
            $this->buyFilled->resetState($row->getId());
            $this->writeWarning(sprintf('%s Error: %s', get_class($e), $e->getMessage()), $output);
        } catch (MaintenanceException | SystemException $e) {
            $this->buyFilled->resetState($row->getId());
            $this->writeWarning(
                sprintf('(%s) %s', $row->getSymbol(), $e->getMessage()),
                $output
            );
            $this->sleep(self::EXCEPTION_DELAY, $this->sleeper, $this->shutdown);
        } catch (MaxIterationsException $e) {
            $this->buyFilled->resetState($row->getId());
            $this->writeWarning(
                sprintf(
                    'Max iterations reached for attempting ForceMaker on %s pair for price of %s.',
                    $row->getSymbol(),
                    $row->getSellPrice()
                ),
                $output
            );
            $this->sleep((int) $input->getOption('maxIterationsDelay'), $this->sleeper, $this->shutdown);
        } catch (\Exception $e) {
            $this->sellSent->setErrorState($row->getId(), get_class($e) . "::{$e->getMessage()}");
            throw $e;
        }
        return $msg;
    }
}
