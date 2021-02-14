<?php

declare(strict_types=1);

/**
 * TODO: Don't like how we've shoved the db interactions directly into this command. I was lazy that day.
 */

namespace Kobens\Gemini\Command\Command\Logger;

use Kobens\Core\Db;
use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetPastTradesInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Kobens\Core\SleeperInterface;

final class TradeHistory extends Command
{
    protected static $defaultName = 'logger:trade-history';

    private const DEFAULT_SYMBOL = 'btcusd';
    private const DEFAULT_DELAY  = 60;
    private const MIN_DELAY      = 30;
    private const MAX_DELAY      = 600;

    private ?TableGateway $table = null;

    private GetPastTradesInterface $pastTrades;

    private EmergencyShutdownInterface $shutdown;

    private SleeperInterface $sleeper;

    private string $symbol;

    private int $delay;

    private Pair $pair;

    public function __construct(
        GetPastTradesInterface $getPastTradesInterface,
        EmergencyShutdownInterface $shutdownInterface,
        SleeperInterface $sleeper
    ) {
        $this->pastTrades = $getPastTradesInterface;
        $this->shutdown = $shutdownInterface;
        $this->sleeper = $sleeper;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Maintains The trading history for a specified symbol.');
        $this->addOption('symbol', 's', InputOption::VALUE_OPTIONAL, 'Symbol to fetch history for.', self::DEFAULT_SYMBOL);
        $this->addOption(
            'delay',
            'd',
            InputOption::VALUE_OPTIONAL,
            \sprintf(
                'Time in seconds to delay between requests once up to date. (%d - %d)',
                self::MIN_DELAY,
                self::MAX_DELAY
            ),
            self::DEFAULT_DELAY
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $this->symbol = $input->getOption('symbol');
        $this->pair = Pair::getInstance($this->symbol);
        $this->delay  = (int) $input->getOption('delay');
        $timestampms  = $this->getLastTradeTimestampMs();

        if ($this->delay < self::MIN_DELAY || $this->delay > self::MAX_DELAY) {
            $this->delay = self::DEFAULT_DELAY;
        }

        try {
            $this->mainLoop($output, $timestampms);
        } catch (\Exception $e) {
            $this->shutdown->enableShutdownMode($e);
            $exitCode = 1;
        }

        $output->writeln(sprintf(
            "<fg=red>%s\tShutdown signal detected - %s (%s)",
            $this->now(),
            self::class,
            $this->symbol
        ));

        return $exitCode;
    }

    private function mainLoop(OutputInterface $output, int $timestampms): void
    {
        while (!$this->shutdown->isShutdownModeEnabled()) {
            $pageFirstTimestampms = $timestampms;

            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln(\sprintf(
                    "\n%s\tFetching page %d (%s)",
                    $this->now(),
                    $timestampms,
                    \gmdate("Y-m-d H:i:s", (int) \substr((string) $timestampms, 0, 10))
                ));
            }

            try {
                $page = $this->pastTrades->getTrades($this->symbol, $timestampms, GetPastTradesInterface::LIMIT_MAX);
            } catch (ConnectionException $e) {
                $output->writeln("<fg=red>{$this->now()}\tError Code: {$e->getCode()}</>");
                $output->writeln("<fg=red>{$this->now()}\tError: {$e->getMessage()}</>");
                $output->writeln("<fg=red>{$this->now()}\tSleeping 10 seconds and trying again...</>");
                \sleep(10);
                continue;
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
                break;
            }

            $i = \count($page);
            $hadResults = $i > 0 ? true : false;

            while ($i > 0) {
                --$i;
                try {
                    if ($this->logTrade($page[$i])) {
                        $output->writeln(sprintf(
                            "%s\tTransaction %d Logged: %s %s %s @ %s %s/%s on %s for order id %s",
                            $this->now(),
                            $page[$i]->tid,
                            $page[$i]->type,
                            $page[$i]->amount,
                            strtoupper($this->pair->getBase()->getSymbol()),
                            $page[$i]->price,
                            strtoupper($this->pair->getBase()->getSymbol()),
                            strtoupper($this->pair->getQuote()->getSymbol()),
                            date('Y-m-d H:i:s', $page[$i]->timestamp),
                            $page[$i]->order_id
                        ));
                    }
                } catch (\Exception $e) {
                    $this->shutdown->enableShutdownMode($e);
                    break;
                }
            }

            if (!$this->shutdown->isShutdownModeEnabled()) {
                if ($hadResults) {
                    $timestampms = $this->getLastTradeTimestampMs();
                    if ($timestampms === $pageFirstTimestampms) {
                        ++$timestampms;
                        if (\count($page) === GetPastTradesInterface::LIMIT_MAX) {
                            // TODO: Support ticket # 1385118
                            // throw new \Exception(
                            //     'Unable to ensure we can fetch the next page. Maximum results per page yielded trade all executing on the same timestamp. Timestamp Milliseconds: '.$timestampms
                            // );
                            $this->logPageLimitError($pageFirstTimestampms);
                            $output->writeln(
                                "\n{$this->now()}\t<fg=red>SKIPPING POTENTIAL $pageFirstTimestampms ORDERS DUE TO MAX PAGE SIZE LIMIT.</>"
                            );
                        }
                    }
                } else {
                    if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                        $output->write(\sprintf(
                            "\n%s\t<fg=green>Trade History for %s pair is up to date. Sleeping for %d seconds...</>\n",
                            $this->now(),
                            $this->symbol,
                            $this->delay
                        ));
                    }
                    $this->sleeper->sleep($this->delay, function(): bool {
                        return $this->shutdown->isShutdownModeEnabled();
                    });
                }
            }
        }
    }

    private function logPageLimitError(int $timestampms): void
    {
        (new TableGateway('trade_history_pageLimitError', Db::getAdapter()))->insert([
            'symbol' => $this->symbol,
            'timestampms' => $timestampms,
        ]);
    }

    private function logTrade(\stdClass $trade): bool
    {
        $result = false;
        try {
            $this->getTable()->insert([
                'tid' => $trade->tid,
                'price' => $trade->price,
                'amount' => $trade->amount,
                'timestampms' => $trade->timestampms,
                'type' => \strtolower($trade->type),
                'aggressor' => $trade->aggressor ? 1 : 0,
                'fee_currency' => $trade->fee_currency,
                'fee_amount' => $trade->fee_amount,
                'order_id' => $trade->order_id,
                'client_order_id' => \property_exists($trade, 'client_order_id') ? $trade->client_order_id : null,
                'trade_date' => \gmdate('Y-m-d H:i:s', $trade->timestamp),
            ]);
            $result = true;
        } catch (InvalidQueryException $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof \PDOException && $previous->getCode() === '23000') {
                // TODO: This may cause us to go over our max_error_count setting, we should do a select first
                // just means we logged it already the prior fetched page
            } else {
                throw $e;
            }
        }
        return $result;
    }

    /**
     * @param string $symbol
     * @return int
     */
    private function getLastTradeTimestampMs(): int
    {
        /** @var \Zend\Db\ResultSet\ResultSet $rows */
        $rows = $this->getTable()->select(function (Select $select) {
            $select->columns(['timestampms']);
            $select->order('timestampms DESC');
            $select->limit(1);
        });
        $timestampms = 0; // With '0' Gemini will return from beginning of trade history if we have none to start from.
        if ($rows->count() === 1) {
            $timestampms = (int) $rows->current()->timestampms;
        }
        return $timestampms;
    }

    private function getTable(): TableGateway
    {
        if (!$this->table) {
            $this->table = new TableGateway('trade_history_' . $this->symbol, Db::getAdapter());
        }
        return $this->table;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
