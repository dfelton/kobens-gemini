<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\Command\Traits\KillFile;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\ArchiveInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellFilledInterface;
use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\ProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Driver\ConnectionInterface;
use Kobens\Gemini\TradeRepeater\Model\Trade\CalculateCompletedProfits;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Core\Db;

final class Archiver extends Command
{
    use SleeperTrait;
    use KillFile;

    private const DEFAULT_DELAY = 2;
    private const KILL_FILE = 'kill_repeater_archiver';

    protected static $defaultName = 'repeater:archiver';

    private ArchiveInterface $archive;

    private ConnectionInterface $connection;

    private SellFilledInterface $sellFilled;

    private EmergencyShutdownInterface $shutdown;

    private SleeperInterface $sleeper;

    private ProcessorInterface $processor;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        SellFilledInterface $sellFilledInterface,
        ArchiveInterface $archiveInterface,
        ConnectionInterface $connectionInterface,
        SleeperInterface $sleeperInterface,
        ProcessorInterface $processorInterface
    ) {
        $this->connection = $connectionInterface;
        $this->archive = $archiveInterface;
        $this->sellFilled = $sellFilledInterface;
        $this->shutdown = $shutdownInterface;
        $this->sleeper = $sleeperInterface;
        $this->processor = $processorInterface;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Archives completed sell orders and marks record for next buy.');
        $this->addOption('delay', 'd', InputOption::VALUE_OPTIONAL, 'Delay in seconds between looking for records.', self::DEFAULT_DELAY);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $delay = (int) $input->getOption('delay');
        if ($delay <= 0) {
            $delay = self::DEFAULT_DELAY;
        }
        while ($this->shutdown->isShutdownModeEnabled() === false && $this->killFileExists(self::KILL_FILE) === false) {
            try {
                $this->mainLoop($output);
                $this->sleep($delay, $this->sleeper, $this->shutdown);
            } catch (\Throwable $e) {
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

    private function mainLoop(OutputInterface $output): void
    {
        /** @var Trade $repeater */
        foreach ($this->sellFilled->getHealthyRecords() as $trade) {
            $this->connection->beginTransaction();
            try {
                $this->archive->addArchive($trade);
                $this->sellFilled->setNextState($trade->getId());
                $this->processor->execute($trade);
                $this->connection->commit();
            } catch (\Throwable $e) {
                if (Db::isInTransaction()) {
                    $this->connection->rollback();
                }
                throw $e;
            }
            $this->reportProfits($output, $trade);
        }
    }

    private function reportProfits(OutputInterface $output, Trade $trade): void
    {
        $pair = Pair::getInstance($trade->getSymbol());
        $base = $pair->getBase()->getSymbol();
        $quote = $pair->getQuote()->getSymbol();
        $output->writeln(sprintf(
            "%s\t(%d)\t<fg=yellow>ARCHIVED\tBUY %s %s @ %s %s/%s --- SELL %s %s @ %s %s/%s</>",
            $this->now(),
            $trade->getId(),
            $trade->getBuyAmount(),
            strtoupper($base),
            $trade->getBuyPrice(),
            strtoupper($base),
            strtoupper($quote),
            $trade->getSellAmount(),
            strtoupper($base),
            $trade->getSellPrice(),
            strtoupper($base),
            strtoupper($quote)
        ));
        $profits = CalculateCompletedProfits::get($trade);
        foreach ([$base, $quote] as $symbol) {
            $profit = $profits[$symbol];
            if (Compare::getResult($profit, '0') === Compare::LEFT_GREATER_THAN) {
                $output->writeln(sprintf(
                    "%s\tProfit: %s %s",
                    $this->now(),
                    $profit,
                    strtoupper($symbol)
                ));
            }
        }
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
