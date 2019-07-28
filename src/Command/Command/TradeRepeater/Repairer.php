<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Gemini\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

/**
 * Class Repairer
 * @package Kobens\Gemini\Command\Command\TradeRepeater
 */
final class Repairer extends ContainerAwareCommand
{
    protected static $defaultName = 'kobens:gemini:trade-repeater:repairer';

    protected function configure(): void
    {
        $this->setDescription('Archives completed sell orders and marks record for next buy.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $db    = $this->find('kobens.db')->getAdapter();
        $table = new TableGateway('gemini_trade_repeater', $db);

        $loop = true;
        while ($loop) {
            try {
                $rows = $table->select(static function (Select $select) {
                    $select->where->in('status', ['BUY_SENT', 'SELL_SENT']);
                    $select->where->isNotNull('note');
                });

                dump(\reset($rows));
                sleep(1);
                break;

                /*foreach ($rows as $row) {
                    dump($row);
                    sleep(1);
                    break 2;
                }*/
            } catch (\Exception $e) {
                echo (new \DateTime())->format('Y-m-d H:i:s') . "\tUnhandled Exception", PHP_EOL;
                dump([
                    'message' => $e->getMessage(),
                    'code'    => $e->getCode(),
                    'class'   => get_class($e),
                    'trace'   => $e->getTraceAsString()
                ]);
                $loop = false;
            }
            sleep(1);
        }
    }
}
