<?php

namespace Kobens\Gemini\Command\Command\Taxes;

use Kobens\Core\Db;
use Kobens\Math\BasicCalculator\Add;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * @deprecated
 */
final class CapitalGains extends Command
{

    /**
     * @var string
     */
    protected static $defaultName = 'taxes:capital-gains';

    /**
     * @var string
     */
    private $symbol;

    protected function configure()
    {
        $this->addArgument('symbol', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new TableGateway('taxes_'.$input->getArgument('symbol').'_sell_log', Db::getAdapter());

        /** @var \Zend\Db\ResultSet\ResultSetInterface $rows */
        $rows = $table->select(function (Select $select)
        {
            $select->where->notIn('tid', (new Select('taxes_form8949'))->columns(['tid']));
            $select->order('tid ASC');
        });
        $allTime = '0';
        foreach ($rows as $row) {
            $data = \json_decode($row['metadata'], true);
            foreach ($data['buys'] as $transaction) {
                $allTime = Add::getResult($allTime, $transaction['gain_or_loss']);
            }
        }
        $output->writeln('All Time Capital Gain / Loss: '. $allTime);
    }

    protected function getTradeRecord(int $tid): array
    {

    }

}
