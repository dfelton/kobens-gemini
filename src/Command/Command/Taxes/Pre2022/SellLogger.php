<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Taxes\Pre2022;

use Kobens\Core\Db;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\Taxes\SaleMetaFactory;
use Kobens\Math\BasicCalculator\Compare;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * TODO: Waaaay too much going on here for one class.
 * TODO: Unable to handle if we send currency into / out of exchange.
 * TODO: Unable to handle if we perform any crypto to crypto transactions.
 * TODO: both the sell-logger and buy-logger should be able to pick up where it left off rather than truncating
 */
final class SellLogger extends Command
{
    protected static $defaultName = 'taxes:pre-2022:sell-logger';

    private ?TableGateway $tblSellLog = null;

    private ?TableGateway $tblBuyLog = null;

    private ?TableGateway $tblTradeHistory = null;

    private string $symbol;

    private int $stop_at_year;

    private SaleMetaFactory $saleMetaFactory;

    public function __construct(
        SaleMetaFactory $saleMetaFactory
    ) {
        $this->saleMetaFactory = $saleMetaFactory;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('symbol', InputArgument::REQUIRED, 'Trading Pair Symbol');
        $this->addArgument('stop_at_year', InputArgument::OPTIONAL, 'Stop At Year', 2022);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $conn = Db::getAdapter()->getDriver()->getConnection();
        $pair = Pair::getInstance($input->getArgument('symbol'));
        $this->symbol = $pair->getSymbol();
        $this->stop_at_year = (int) $input->getArgument('stop_at_year');

        do {
            $rows = $this->getSellRecordsToLog();
            foreach ($rows as $sell) {
                $sellRemaining = $sell['amount'];

                try {
                    $conn->beginTransaction();
                    do {
                        $buy = $this->getNextBuyForSale();
                        if ($buy === []) {
                            throw new \Exception('missing buy order to match with sell.');
                        }

                        $meta = $this->saleMetaFactory->get($sellRemaining, $buy, (array) $sell);

                        $this->getBuyLogTable()->update(
                            ['amount_remaining' => $meta->getBuyRemaining()],
                            ['tid' => $buy['tid']]
                        );

                        $this->getSellLogTable()->insert([
                            'sell_tid' => $sell['tid'],
                            'buy_tid' => $buy['tid'],
                            'amount' => $meta->getLogAmount(),
                            'cost_basis' => $meta->getCostBasis(),
                            'proceeds' => $meta->getProceeds(),
                            'capital_gain' => $meta->getGainLoss(),
                        ]);

                        $sellRemaining = $meta->getSellRemaining();

                    } while (Compare::getResult($sellRemaining, '0') !== Compare::EQUAL);

                    $output->writeln(\sprintf('Commiting data for sale of transaction id %s', $sell['tid']));

                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
        } while ($rows->count() > 0);

        $output->writeln('Sale Data Generated');
        return 0;
    }

    private function getNextBuyForSale(): array
    {
        $rows = $this->getBuyLogTable()->select(function (Select $select) {
            $select->where->notEqualTo('amount_remaining', '0');
            $select->order('tid ASC');
            $select->limit(1);
        });
        $data = [];
        if ($rows->count() === 1) {
            foreach ($rows as $row) {
                $data = (array) $row;
                break;
            }
            $rows = $this->getTradeHistoryTable()->select(function (Select $select) use ($data) {
                $select->where->equalTo('tid', $data['tid']);
            });
            foreach ($rows as $row) {
                $data = \array_merge($data, (array) $row);
                break;
            }
        }
        \ksort($data);
        return $data;
    }

    private function getSellRecordsToLog(): \Zend\Db\ResultSet\ResultSetInterface
    {
        /** @var \Zend\Db\ResultSet\ResultSetInterface $rows */
        $rows = $this->getTradeHistoryTable()->select(function (Select $select) {
            $select->where->notIn(
                'tid',
                (new Select('taxes_' . $this->symbol . '_sell_log'))->columns(['sell_tid'])
            );
            $select->where->equalTo('type', 'sell');
            $select->where->lessThan('trade_date', $this->stop_at_year . '-01-01 00:00:00');
            $select->where->lessThan('trade_date', '2022-01-01 00:00:00');
            $select->order('tid ASC');
            $select->limit(100);
        });
        return $rows;
    }

    private function getSellLogTable(): TableGateway
    {
        if (!$this->tblSellLog) {
            $this->tblSellLog = new TableGateway('taxes_' . $this->symbol . '_sell_log', Db::getAdapter());
        }
        return $this->tblSellLog;
    }

    private function getBuyLogTable(): TableGateway
    {
        if (!$this->tblBuyLog) {
            $this->tblBuyLog = new TableGateway('taxes_' . $this->symbol . '_buy_log', Db::getAdapter());
        }
        return $this->tblBuyLog;
    }

    private function getTradeHistoryTable(): TableGateway
    {
        if (!$this->tblTradeHistory) {
            $this->tblTradeHistory = new TableGateway('trade_history_' . $this->symbol, Db::getAdapter());
        }
        return $this->tblTradeHistory;
    }
}
