<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Gemini\TradeRepeater\DataResource\ArchiveInterface;
use Kobens\Gemini\TradeRepeater\DataResource\SellFilledInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Driver\ConnectionInterface;

final class Archiver extends Command
{
    protected static $defaultName = 'trade-repeater:archiver';

    /**
     * @var ArchiveInterface
     */
    private $archive;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var SellFilledInterface
     */
    private $sellFilled;

    /**
     * @var EmergencyShutdownInterface
     */
    private $shutdown;

    public function __construct(
        ArchiveInterface $archiveInterface,
        SellFilledInterface $sellFilledInterface,
        EmergencyShutdownInterface $shutdownInterface,
        ConnectionInterface $connectionInterface
    ) {
        parent::__construct();
        $this->connection = $connectionInterface;
        $this->archive = $archiveInterface;
        $this->sellFilled = $sellFilledInterface;
        $this->shutdown = $shutdownInterface;
    }

    protected function configure()
    {
        $this->setDescription('Archives completed sell orders and marks record for next buy.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (!$this->shutdown->isShutdownModeEnabled()) {
            try {
                $this->mainLoop($output);
                \sleep(1);
            } catch (\Exception $e) {
                $this->connection->rollback();
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln("<fg=red>Shutdown Signal Detected</>");
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
