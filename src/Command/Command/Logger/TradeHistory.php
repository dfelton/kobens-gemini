<?php

declare(strict_types=1);

/**
 * TODO: Don't like how we've shoved the db interactions directly into this command. I was lazy that day.
 */

namespace Kobens\Gemini\Command\Command\Logger;

use Kobens\Core\Db;
use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetPastTradesInterface;
use Kobens\Gemini\Command\Traits\GetNow;
use Kobens\Gemini\Command\Traits\GetIntArg;
use Kobens\Gemini\Command\Traits\KillFile;
use Kobens\Gemini\Command\Traits\TradeRepeater\ExitProgram;
use Kobens\Gemini\Exchange\Currency\Pair;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

final class TradeHistory extends Command
{
    use KillFile;
    use ExitProgram;
    use GetNow;
    use GetIntArg;

    protected static $defaultName = 'logger:trade-history';

    private const KILL_FILE = 'kill_logger_trade_history';

    private const DELAY_DEFAULT  = 60;
    private const MIN_DELAY      = 30;
    private const MAX_DELAY      = 600;

    private ?TableGateway $table = null;

    private GetPastTradesInterface $pastTrades;

    private EmergencyShutdownInterface $shutdown;

    private SleeperInterface $sleeper;

    private string $symbol;

    private int $delay;

    private Pair $pair;

    private Adapter $adapter;

    private ?int $lastInsertedTransactionId = null;

    public function __construct(
        GetPastTradesInterface $getPastTradesInterface,
        EmergencyShutdownInterface $shutdownInterface,
        SleeperInterface $sleeper,
        Adapter $adapter
    ) {
        $this->pastTrades = $getPastTradesInterface;
        $this->shutdown = $shutdownInterface;
        $this->sleeper = $sleeper;
        $this->adapter = $adapter;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Maintains The trading history for a specified symbol.');
        $this->addArgument('symbol', InputArgument::REQUIRED, 'Symbol to fetch history for.');
        $this->addOption(
            'delay',
            'd',
            InputOption::VALUE_OPTIONAL,
            \sprintf(
                'Time in seconds to delay between requests once up to date. (%d - %d)',
                self::MIN_DELAY,
                self::MAX_DELAY
            ),
            self::DELAY_DEFAULT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        if ($this->shutdown->isShutdownModeEnabled() === false) {
            $this->symbol = $input->getArgument('symbol');
            $this->pair = Pair::getInstance($this->symbol);
            $this->delay = $this->getIntArg($input, 'delay', self::DELAY_DEFAULT, self::MIN_DELAY, self::MAX_DELAY);
            $output->writeln(sprintf(
                "%s\tStarting trade history logger %s",
                $this->getNow(),
                $input->getArgument('symbol')
            ));
            $this->main($input, $output);
        }
        $this->outputExit($output, $this->shutdown, self::KILL_FILE, sprintf(' (%s)', $input->getArgument('symbol')));
        return $exitCode;
    }

    protected function main(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $timestampms = $this->getLastTradeTimestampMs();
        try {
            $this->mainLoop($output, $timestampms);
        } catch (\Throwable $e) {
            $this->shutdown->enableShutdownMode($e);
            $exitCode = 1;
        }
        return $exitCode;
    }

    private function mainLoop(OutputInterface $output, int $timestampms): void
    {
        while (!$this->shutdown->isShutdownModeEnabled()) {
            $pageFirstTimestampms = $timestampms;

            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln(\sprintf(
                    "\n%s\tFetching page %d (%s)",
                    $this->getNow(),
                    $timestampms,
                    \gmdate("Y-m-d H:i:s", (int) \substr((string) $timestampms, 0, 10))
                ));
            }

            try {
                $page = $this->pastTrades->getTrades($this->symbol, $timestampms, GetPastTradesInterface::LIMIT_MAX);
            } catch (ConnectionException $e) {
                $output->writeln("<fg=red>{$this->getNow()}\tError Code: {$e->getCode()}</>");
                $output->writeln("<fg=red>{$this->getNow()}\tError: {$e->getMessage()}</>");
                $output->writeln("<fg=red>{$this->getNow()}\tSleeping 10 seconds and trying again...</>");
                $this->sleeper->sleep(
                    10,
                    function (): bool {
                        return $this->shutdown->isShutdownModeEnabled();
                    }
                );
                continue;
            }

            $i = \count($page);
            $hadResults = false;

            while ($i > 0) {
                --$i;
                if ($this->logTrade($page[$i])) {
                    $output->writeln(sprintf(
                        "%s\tTransaction %d Logged: %s %s %s @ %s %s/%s on %s for order id %s",
                        $this->getNow(),
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
                    $hadResults = true;
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
                            $output->writeln(sprintf(
                                "%s\t<fg=red>SKIPPING POTENTIAL %d ORDERS DUE TO MAX PAGE SIZE LIMIT.</>",
                                $this->getNow(),
                                $timestampms
                            ));
                        }
                    }
                } else {
                    if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                        $output->write(\sprintf(
                            "\n%s\t<fg=green>Trade History for %s pair is up to date. Sleeping for %d seconds...</>\n",
                            $this->getNow(),
                            $this->symbol,
                            $this->delay
                        ));
                    }

                    // Cheap way to ping the database before we sleep.
                    $this->adapter->query('SELECT 1')->execute();

                    $this->sleeper->sleep(
                        $this->delay,
                        function (): bool {
                            return $this->shutdown->isShutdownModeEnabled();
                        }
                    );
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
        if ($this->transactionExists($trade->tid)) {
            return false;
        }
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
        $this->lastInsertedTransactionId = $trade->tid;
        return true;
    }

    private function transactionExists(int $tid): bool
    {
//         $exists = false;
//         if ($this->lastInsertedTransactionId === null) {
            $result = $this->getTable()->select(function (Select $select) use ($tid): void {
                $select->where->equalTo('tid', $tid);
            });
            $exists = $result->count() === 1;
//             if ($exists) {
//                 $this->lastInsertedTransactionId = $tid;
//             }
//         } else {
//             $exists = $tid === $this->lastInsertedTransactionId;
//         }
        return $exists;
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
}
