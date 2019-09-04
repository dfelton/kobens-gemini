<?php

namespace Kobens\Gemini\Command\Command\Order\Logger;

use Kobens\Core\Db;
use Kobens\Gemini\Api\Rest\Request\Order\Status\OrderStatus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

final class CheckLength extends Command
{
    protected static $defaultName = 'order-logger:check-length';

    /**
     * @var TableGateway
     */
    private $table;

    protected function configure()
    {
        $this->setDescription('Checks max lengths of values');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = ['amount' => [], 'fee' => []];
        foreach ($this->getRows() as $row) {
            if (($pos = \strpos($row['amount'], '.')) !== false) {
                $length = \strlen(\substr($row['amount'], $pos+1));
                if ($length > 8) {
                    $data['amount'][$row->tid] = $row->amount;
                }
                unset($length);
            }
            unset ($pos);

            if (($pos = \strpos($row['fee_amount'], '.')) !== false) {
                $length = \strlen(\substr($row['fee_amount'], $pos+1));
                if ($length > 14) {
                    $data['fee'][$row->tid] = $row->fee_amount;
                }
                unset($length);
            }
            unset ($pos);
        }
        \Zend\Debug\Debug::dump($data);
        $data = $this->getInfo(array_merge(\array_keys($data['amount']), \array_keys($data['fee'])));
        foreach ($data as $row) {
            $orderStatus = new OrderStatus($row['order_id']);
            $status = $orderStatus->getResponse();
            $data =  \json_decode($status['body'], true);
            echo $data['order_id'],"\t",$data['type'],PHP_EOL;
        }
    }

    private function getInfo(array $ids): array
    {
        $rows = $this->getTable()->select(function (Select $select) use ($ids)
        {
            $select->where->in('tid', $ids);
            $select->order('order_id ASC');
        });
        $data = [];
        foreach ($rows as $row) {
            $data[] = (array) $row;
        }
        return $data;
    }

    private function getRows(): \Generator
    {
        $rows = $this->getTable()->select(function (Select $select)
        {
            $select->columns(['tid','amount','fee_amount']);
            $select->order('tid ASC');
        });
        foreach ($rows as $row) {
            yield $row;
        }
    }

    private function getTable()
    {
        if (!$this->table) {
            $this->table = new TableGateway('trade_history', Db::getAdapter());
        }
        return $this->table;
    }

}