<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Core\Db;
use Kobens\Gemini\TradeRepeater\DataResource\SellFilled;
use Kobens\Gemini\TradeRepeater\DataResource\Archive;
use Kobens\Gemini\TradeRepeater\DataResource\BuyPlaced;
use Kobens\Gemini\TradeRepeater\DataResource\BuySent;
use Kobens\Gemini\TradeRepeater\DataResource\BuyFilled;
use Kobens\Gemini\TradeRepeater\DataResource\SellSent;
use Kobens\Gemini\TradeRepeater\DataResource\SellPlaced;
use Kobens\Gemini\TradeRepeater\DataResource\BuyReady;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

final class Repairer extends Command
{
    protected static $defaultName = 'kobens:gemini:trade-repeater:repairer';

    protected function configure()
    {
        $this->setDescription('Archives completed sell orders and marks record for next buy.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db = Db::getAdapter();
        $table = new TableGateway('gemini_trade_repeater', $db);

        $loop = true;
        while ($loop) {
            try {

                $rows = $table->select(function (Select $select) {
                    $select->where->in('status', ['BUY_SENT', 'SELL_SENT']);
                    $select->where->isNotNull('note');
                });

                foreach ($rows as $row) {
                    \Zend\Debug\Debug::dump($row);
                    sleep(1);break 2;
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
