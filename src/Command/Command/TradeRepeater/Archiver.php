<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\Db;
use Kobens\Gemini\TradeRepeater\DataResource\Archive;
use Kobens\Gemini\TradeRepeater\DataResource\SellFilled;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Archiver extends Command
{
    protected static $defaultName = 'trade-repeater:archiver';

    protected function configure()
    {
        $this->setDescription('Archives completed sell orders and marks record for next buy.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sellFilled = new SellFilled();
        $archiver = new Archive();
        $conn = Db::getAdapter()->getDriver()->getConnection();
        $inTransaction = false;

        $loop = true;
        while ($loop) {
            try {
                foreach ($sellFilled->getHealthyRecords() as $row) {
                    $conn->beginTransaction();
                    $inTransaction = true;

                    $archiver->addArchive((array) $row);

                    $sellFilled->setNextState($row->id);
                    $output->writeln((new \DateTime())->format('Y-m-d H:i:s')."\tTrade Repeater Record {$row->id} moved to BUY_READY state.");

                    $conn->commit();
                    $inTransaction = false;
                }
            } catch (\Exception $e) {
                if ($inTransaction) {
                    $conn->rollback();
                }
                \Zend\Debug\Debug::dump(
                    [
                        'message' => $e->getMessage(),
                        'code' => $e->getCode(),
                        'class' => \get_class($e),
                        'trace' => $e->getTraceAsString()
                    ],
                    (new \DateTime())->format('Y-m-d H:i:s')."\tUnhandled Exception"
                );
                $loop = false;
            }
            \sleep(1);
        }
    }

}
