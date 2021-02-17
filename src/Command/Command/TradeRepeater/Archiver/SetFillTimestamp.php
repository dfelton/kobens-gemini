<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\Archiver;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\Command\Traits\KillFile;
use Kobens\Gemini\Exchange\Currency\Pair;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Adapter;
use Kobens\Gemini\Command\Traits\TradeRepeater\ExitProgram;

final class SetFillTimestamp extends Command
{
    use KillFile;
    use ExitProgram;

    private const KILL_FILE = 'kill_repeater_archiver_set_fill_timestamp';

    private Adapter $adapter;

    private SleeperInterface $sleeper;

    private EmergencyShutdownInterface $shutdown;

    public function __construct(
        Adapter $adapter,
        SleeperInterface $sleeper,
        EmergencyShutdownInterface $shutdown
    ) {
        $this->adapter = $adapter;
        $this->sleeper = $sleeper;
        $this->shutdown = $shutdown;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('repeater:archiver:fill-time');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        while ($this->shutdown->isShutdownModeEnabled() === false && $this->killFileExists(self::KILL_FILE) === false) {
            try {
                foreach ($this->getRowsForUpdate() as $row) {
                    $this->logFillTime($row);
                }
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
                $exitCode = 1;
            }
            if ($this->shutdown->isShutdownModeEnabled() === false) {
                $this->sleeper->sleep(60, function (): bool {
                    return $this->shutdown->isShutdownModeEnabled();
                });
            }
        }
        $this->outputExit($output, $this->shutdown, self::KILL_FILE);
        return $exitCode;
    }

    private function getRowsForUpdate(): \Generator
    {
        do {
            /** @var \Zend\Db\Adapter\Driver\Pdo\Statement $stmt */
            /** @var \Zend\Db\ResultSet\ResultSet $rows */
            $stmt = $this->adapter->query(
                'SELECT id, symbol, sell_order_id FROM trade_repeater_archive WHERE sell_fill_timestamp IS NULL LIMIT 1000'
            );
            $rows = $stmt->execute();
            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    if ($this->shutdown->isShutdownModeEnabled() === true) {
                        break;
                    }
                    yield $row;
                }
            }
            $this->sleeper->sleep(10, function (): bool {
                return $this->shutdown->isShutdownModeEnabled();
            });
        } while ($rows->count() > 0 && $this->shutdown->isShutdownModeEnabled() === false);
    }

    private function logFillTime(array $row): void
    {
        /** @var \Zend\Db\Adapter\Driver\Pdo\Statement $stmt */
        /** @var \Zend\Db\ResultSet\ResultSet $rows */
        $stmt = $this->adapter->query(sprintf(
            'SELECT timestampms FROM %s WHERE order_id = :order_id ORDER BY timestampms DESC limit 1',
            'trade_history_' . Pair::getInstance($row['symbol'])->getSymbol()
        ));
        $rows = $stmt->execute(['order_id' => $row['sell_order_id']]);
        // If there isn't one, it probably has simply not been logged yet.
        if ($rows->count() === 1) {
            $timestamp = (int) substr($rows->current()['timestampms'], 0, -3);
            $date = date('Y-m-d H:i:s', $timestamp);
            $stmt = $this->adapter->query(
                'UPDATE trade_repeater_archive SET sell_fill_timestamp = :timestamp WHERE id = :id'
            );
            $stmt->execute(['timestamp' => $date, 'id' => $row['id']]);
        }
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
