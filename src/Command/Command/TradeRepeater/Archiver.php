<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\ArchiveInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellFilledInterface;
use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\ProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Driver\ConnectionInterface;

final class Archiver extends Command
{
    use SleeperTrait;

    private const DEFAULT_DELAY = 2;

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
        return 0;
    }

    private function mainLoop(OutputInterface $output): void
    {
        /** @var \Kobens\Gemini\TradeRepeater\Model\Trade $row */
        foreach ($this->sellFilled->getHealthyRecords() as $row) {
            $this->connection->beginTransaction();
            try {
                $this->archive->addArchive($row);
                $this->sellFilled->setNextState($row->getId());
                $this->processor->execute($row);
                $this->connection->commit();
            } catch (\Throwable $e) {
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

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
