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

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        SellFilledInterface $sellFilledInterface,
        ArchiveInterface $archiveInterface,
        ConnectionInterface $connectionInterface,
        SleeperInterface $sleeperInterface
    ) {
        $this->connection = $connectionInterface;
        $this->archive = $archiveInterface;
        $this->sellFilled = $sellFilledInterface;
        $this->shutdown = $shutdownInterface;
        $this->sleeper = $sleeperInterface;
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
                $this->connection->rollback();
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln("\n<fg=red>{$this->now()}\tShutdown signal detected.\n");
    }

    private function mainLoop(OutputInterface $output): void
    {
        foreach ($this->sellFilled->getHealthyRecords() as $row) {
            $meta = \json_decode($row->meta);
            $this->connection->beginTransaction();
            $this->archive->addArchive(
                $row->symbol,
                $row->buy_client_order_id,
                $row->buy_order_id,
                $row->buy_amount,
                $meta->buy_price,
                $row->sell_client_order_id,
                $row->sell_order_id,
                $row->sell_amount,
                $meta->sell_price
            );
            $this->sellFilled->setNextState($row->id);
            $this->connection->commit();
            $output->writeln($this->now()."\t({$row->id}) archived and moved to BUY_READY state.");
        }
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
