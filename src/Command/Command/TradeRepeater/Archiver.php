<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\ArchiveInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellFilledInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Driver\ConnectionInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Update;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade\CalculateCompletedProfits;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount\Calculator;

final class Archiver extends Command
{
    use SleeperTrait;

    private const DEFAULT_DELAY = 5;

    protected static $defaultName = 'trade-repeater:archiver';

    private ArchiveInterface $archive;

    private ConnectionInterface $connection;

    private SellFilledInterface $sellFilled;

    private EmergencyShutdownInterface $shutdown;

    private SleeperInterface $sleeper;

    private Update $update;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        SellFilledInterface $sellFilledInterface,
        ArchiveInterface $archiveInterface,
        ConnectionInterface $connectionInterface,
        SleeperInterface $sleeperInterface,
        Update $update
    ) {
        $this->connection = $connectionInterface;
        $this->archive = $archiveInterface;
        $this->sellFilled = $sellFilledInterface;
        $this->shutdown = $shutdownInterface;
        $this->sleeper = $sleeperInterface;
        $this->update = $update;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Archives completed sell orders and marks record for next buy.');
        $this->addOption('delay', 'd', InputOption::VALUE_OPTIONAL, 'Delay in seconds between looking for records.', self::DEFAULT_DELAY);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $delay = (int) $input->getOption('delay');
        if ($delay <= 0) {
            $delay = self::DEFAULT_DELAY;
        }
        while (!$this->shutdown->isShutdownModeEnabled()) {
            try {
                $this->mainLoop($output);
                $this->sleep($delay, $this->sleeper, $this->shutdown);
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln(sprintf(
            "<fg=red>%s\tShutdown signal detected - %s",
            $this->now(),
            self::class
        ));
    }

    private function mainLoop(OutputInterface $output): void
    {
        /** @var \Kobens\Gemini\TradeRepeater\Model\Trade $row */
        foreach ($this->sellFilled->getHealthyRecords() as $row) {
            $meta = \json_decode($row->getMeta());
            $this->connection->beginTransaction();
            try {

                $this->archive->addArchive(
                    $row->getSymbol(),
                    $row->getBuyClientOrderId(),
                    $row->getBuyOrderId(),
                    $row->getBuyAmount(),
                    (string) $meta->buy_price,
                    $row->getSellClientOrderId(),
                    $row->getSellOrderId(),
                    $row->getSellAmount(),
                    (string) $meta->sell_price
                );
                $this->sellFilled->setNextState($row->getId());
                $this->processProfits($row);

                $this->connection->commit();
            } catch (\Error $e) {
                $this->connection->rollback();
                throw $e;
            }
            $output->writeln(sprintf(
                "%s\t(%d)\t<fg=yellow>ARCHIVED</>",
                $this->now(),
                $row->getId()
            ));
        }
    }

    /**
     * TODO: implement plans for other 50% of profits
     * TODO: would be nice if the $sellAmount was adjusted too... keep ratio fiat/crypto outcome rather than piling on only more fiat
     *
     * @param Trade $trade
     */
    private function processProfits(Trade $trade): void
    {
        $pair = Pair::getInstance($trade->getSymbol());
        if ($pair->getQuote()->getSymbol() !== 'usd') {
            return;
        }

        $usd = CalculateCompletedProfits::get($trade)['usd'];

        // TODO: Plan is 50%, we're doing 95% to start.
        $reinvestInSamePosition = Multiply::getResult($usd, '0.95');
        $calculation = new Calculator($pair, $reinvestInSamePosition, $trade->getBuyPrice());

        if (Compare::getResult($calculation->getBaseAmount(), $pair->getMinOrderIncrement()) !== Compare::RIGHT_GREATER_THAN) {
            $buyAmount = Add::getResult($trade->getBuyAmount(), $calculation->getBaseAmount());
            $sellAmount = Add::getResult($trade->getSellAmount(), $calculation->getBaseAmount());
            $this->update->updateAmounts($trade->getId(), $buyAmount, $sellAmount);
        }
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
