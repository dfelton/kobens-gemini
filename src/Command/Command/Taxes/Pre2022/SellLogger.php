<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Taxes\Pre2022;

use Kobens\Core\Db;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\Exchange\Order\Fee\Trade\BPS;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    protected function configure(): void
    {
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Trading Pair Symbol', 'btcusd');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $conn = Db::getAdapter()->getDriver()->getConnection();
        $pair = Pair::getInstance($input->getOption('symbol'));
        $this->symbol = $pair->getSymbol();

        $bps = BPS::getInstance();
        do {
            $rows = $this->getSellRecordsToLog();
            foreach ($rows as $sell) {
                $buyIds = [];
                $sellRemaining = $sell['amount'];

                try {
                    $conn->beginTransaction();
                    do {
                        $buy = $this->getNextBuyForSale($buyIds);
                        if ($buy === []) {
                            throw \Exception('missing buy order to match with sell.');
                        }

                        switch (Compare::getResult($sellRemaining, $buy['amount_remaining'])) {
                            case Compare::RIGHT_GREATER_THAN: // buy is larger than sell remaining
                                $logAmount = $sellRemaining;
                                $buyRemaining = Subtract::getResult($buy['amount_remaining'], $sellRemaining);
                                $sellRemaining = '0';
                                break;

                            case Compare::EQUAL: // they are equal
                                $logAmount = $sellRemaining;
                                $buyRemaining = '0';
                                $sellRemaining = '0';
                                break;

                            case Compare::LEFT_GREATER_THAN: // sell remaining is larger than buy
                                $logAmount = $buy['amount_remaining'];
                                $buyRemaining = '0';
                                $sellRemaining = Subtract::getResult($sellRemaining, $buy['amount_remaining']);
                                break;

                            default:
                                throw \ErrorException();
                        }

                        $sellToLogQuoteAmount = Multiply::getResult($logAmount, $sell['price']);
                        if ($sell['fee_amount'] === '0.00') {
                            $sellToLogFee = '0.00';
                        } else {
                            try {
                                $sellFeePercent = $bps->getRate($sell['amount'], $sell['price'], $sell['fee_amount']);
                            } catch (\LogicException $e) {
                                $sellFeePercent = '0.001';
                            }
                            $sellToLogFee = Multiply::getResult($sellToLogQuoteAmount, $sellFeePercent);
                        }

                        $buyToLogQuoteAmount  = Multiply::getResult($logAmount, $buy['price']);
                        if ($buy['fee_amount'] === '0.00') {
                            $buyToLogFee = '0.00';
                        } else {
                            try {
                                $buyFeePercent  = $bps->getRate($buy['amount'], $buy['price'], $buy['fee_amount']);
                            } catch (\LogicException $e) {
                                $buyFeePercent = '0.001';
                            }
                            $buyToLogFee = Multiply::getResult($buyToLogQuoteAmount, $buyFeePercent);
                        }

                        $costBasis   = Add::getResult($buyToLogQuoteAmount, $buyToLogFee);
                        $proceeds    = Subtract::getResult($sellToLogQuoteAmount, $sellToLogFee);
                        $capitalGain = Subtract::getResult($proceeds, $costBasis);

                        if (Compare::getResult($logAmount, '0') === Compare::RIGHT_GREATER_THAN) {
                            throw new \LogicException('Negative Log Amount');
                        }
                        if (Compare::getResult($costBasis, '0') === Compare::RIGHT_GREATER_THAN) {
                            throw new \LogicException('Negative Cost Basis');
                        }
                        if (Compare::getResult($proceeds, '0') === Compare::RIGHT_GREATER_THAN) {
                            throw new \LogicException('Negative Proceeds');
                        }
                        if (Compare::getResult($sellToLogFee, '0') === Compare::RIGHT_GREATER_THAN) {
                            throw new \LogicException('Negative Sell Fee');
                        }
                        if (Compare::getResult($buyToLogFee, '0') === Compare::RIGHT_GREATER_THAN) {
                            throw new \LogicException('Negative Buy Fee');
                        }
                        if (Compare::getResult($sellRemaining, '0') === Compare::RIGHT_GREATER_THAN) {
                            throw new \LogicException('Negative Sell Remaining Amount');
                        }
                        if (Compare::getResult($buyRemaining, '0') === Compare::RIGHT_GREATER_THAN) {
                            throw new \LogicException('Negative Buy Remaining Amount');
                        }

                        $this->getBuyLogTable()->update(
                            ['amount_remaining' => $buyRemaining],
                            ['tid' => $buy['tid']]
                        );

                        $this->getSellLogTable()->insert([
                            'sell_tid' => $sell['tid'],
                            'buy_tid' => $buy['tid'],
                            'amount' => $logAmount,
                            'cost_basis' => $costBasis,
                            'proceeds' => $proceeds,
                            'capital_gain' => $capitalGain,
                        ]);

                        $buyIds[] = $buy['tid'];

                        unset($capitalGain);
                        unset($proceeds);
                        unset($sellToLogFee);
                        unset($sellToLogQuoteAmount);
                        unset($costBasis);
                        unset($buyToLogFee);
                        unset($buyToLogQuoteAmount);
                        unset($buyFeePercent);
                        unset($sellFeePercent);
                        unset($logAmount);
                        unset($buy);
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

    private function getNextBuyForSale(array $omitTransactionIds = []): array
    {
        $rows = $this->getBuyLogTable()->select(function (Select $select) use ($omitTransactionIds) {
            $select->where->notEqualTo('amount_remaining', '0');
            if ($omitTransactionIds !== []) {
                $select->where->notIn('tid', $omitTransactionIds);
            }
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
