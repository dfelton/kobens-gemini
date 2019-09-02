<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

final class Repairer extends Command
{
    protected static $defaultName = 'trade-repeater:repairer';

    protected function configure()
    {
//         $this->setDescription('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db = Db::getAdapter();
        $table = new TableGateway('trade_repeater', $db);

        $loop = true;
        while ($loop) {
            try {

                $rows = $table->select(function (Select $select) {
                    $select->where->in('status', ['BUY_SENT', 'SELL_SENT']);
                    $select->where->isNotNull('note');
                });

                foreach ($rows as $row) {
                    \Zend\Debug\Debug::dump($row);
                    \sleep(1);break 2;
                }

            } catch (\Exception $e) {
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
