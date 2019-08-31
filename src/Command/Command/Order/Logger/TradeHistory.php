<?php

/**
 * TODO: Don't like how we've shoved the db interactions directly into this command. I was lazy that day.
 */

namespace Kobens\Gemini\Command\Command\Order\Logger;

use Kobens\Core\Db;
use Kobens\Gemini\Api\Rest\Request\Order\Status\PastTrades;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

final class TradeHistory extends Command
{
    protected static $defaultName = 'order:logger:trade-history';

    /**
     * @var TableGateway
     */
    private $table;

    /**
     * @var PastTrades
     */
    private $pastTradesApi;

    /**
     * @var string
     */
    private $symbol;

    protected function configure()
    {
        $this->setDescription('Maintains The trading history for a specified symbol.');
        $this->addArgument('symbol', InputArgument::REQUIRED, 'Symbol to fetch history for.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->symbol = $input->getArgument('symbol');

        $timestampms = $this->getLastTradeTimestampMs();
        $loop = true;
        do {
            $pageFirstTimestampms = $timestampms;

            $output->writeln(\sprintf(
                "%s\tFetching page %d (%s)",
                (new \DateTime())->format('Y-m-d H:i:s'),
                $timestampms,
                \gmdate("Y-m-d H:i:s \U\T\C", \substr($timestampms, 0, 10))
            ));
            $page = $this->getPage($timestampms);
            $i = \count($page);
            $hadResults = $i > 0 ? true : false;

            while ($i > 0) {
                $i--;
                $trade = $page[$i];
                $this->logTrade($trade);
                if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                    $this->outputUpdate($trade, $output);
                }
            }

            if ($hadResults) {
                $timestampms = $this->getLastTradeTimestampMs();
                if ($timestampms === $pageFirstTimestampms) {
                    $timestampms++;
                    if (\count($page) === PastTrades::LIMIT_MAX) {
                        // TODO: Support ticket # 1385118
                        $this->logPageLimitError($pageFirstTimestampms);
                        $output->writeln(\sprintf(
                            "%s\t<fg=red>%s</>",
                            (new \DateTime())->format('Y-m-d H:i:s'),
                            'WARNING. SKIPPING POTENTIAL ORDERS DUE TO MAX PAGE SIZE LIMIT.'
                        ));
                        \sleep(5);
    //                     throw new \Exception(
    //                         'Unable to ensure we can fetch the next page. Maximum results per page yielded trade all executing on the same timestamp. Timestamp Milliseconds: '.$timestampms
    //                     );
                    }
                }
            } else {
                $output->write(\sprintf(
                    "%s\t<fg=green>Trade History for %s pair is up to date. Sleeping for one minute...</>",
                    (new \DateTime())->format('Y-m-d H:i:s'),
                    $this->symbol
                ));
                \sleep(60);
            }
        } while ($loop);
    }

    private function outputUpdate(array $trade, OutputInterface $output): void
    {
        $date = $trade['timestampms']/1000;
        $date = explode('.', $date);
        $formattedDate = \date('Y-m-d H:i:s', $date[0]) . (isset($date[1]) ? '.'.$date[1] : '');

        $output->writeln(\sprintf(
            "%s\tLogged transaction %s for order %s. %s order for amount of %s at a price of %s per unit. Which occurred on %s. Fee: %s %s",
            (new \DateTime())->format('Y-m-d H:i:s'),
            "<fg=yellow>{$trade['tid']}</>",
            "<fg=yellow>{$trade['order_id']}</>",
            $trade['type'] === 'Buy' ? "<fg=green>Buy</>" : "<fg=red>{$trade['type']}</>",
            "<fg=yellow>{$trade['amount']}</>",
            "<fg=yellow>{$trade['price']}</>",
            "<fg=yellow>$formattedDate</>",
            "<fg=yellow>{$trade['fee_amount']}</>",
            "<fg=yellow>{$trade['fee_currency']}</>"
        ));
    }

    private function logPageLimitError(int $timestampms): void
    {
        (new TableGateway('gemini_trade_history_pageLimitError', Db::getAdapter()))->insert([
            'symbol' => $this->symbol,
            'timestampms' => $timestampms,
        ]);
    }

    private function logTrade(array $trade)
    {
        try {
            $this->getTable()->insert([
                'transaction_id' => $trade['tid'],
                'symbol' => $this->symbol,
                'price' => $trade['price'],
                'amount' => $trade['amount'],
                'timestampms' => $trade['timestampms'],
                'side' => \strtolower($trade['type']),
                'fee_currency' => $trade['fee_currency'],
                'fee_amount' => $trade['fee_amount'],
                'order_id' => $trade['order_id'],
                'client_order_id' => \array_key_exists('client_order_id', $trade) ? $trade['client_order_id'] : null
            ]);
        } catch (InvalidQueryException $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof \PDOException && $previous->getCode() === '2300') {
                // just means we logged it already the prior fetched page
            }
        }
    }

    private function getPage(int $timestampms)
    {
        return \json_decode($this->getModel()->setTimestamp($timestampms)->getResponse()['body'], true);
    }

    private function getModel(): PastTrades
    {
        if (!$this->pastTradesApi) {
            $this->pastTradesApi = new PastTrades($this->symbol, 0, PastTrades::LIMIT_MAX);
        }
        return $this->pastTradesApi;
    }

    /**
     * @param string $symbol
     * @return int
     */
    private function getLastTradeTimestampMs(): int
    {
        /** @var \Zend\Db\ResultSet\ResultSet $rows */
        $rows = $this->getTable()->select(function (Select $select)
        {
            $select->columns(['timestampms']);
            $select->where->equalTo('symbol', $this->symbol);
            $select->order('timestampms DESC');
            $select->limit(1);
        });
        $timestampms = 0; // With '0' Gemini will return from beginning of trade history if we have none to start from.
        if ($rows->count() === 1) {
            $timestampms = (int) $rows->toArray()[0]['timestampms'];
        }
        return $timestampms;
    }

    private function getTable()
    {
        if (!$this->table) {
            $this->table = new TableGateway('gemini_trade_history', Db::getAdapter());
        }
        return $this->table;
    }

}