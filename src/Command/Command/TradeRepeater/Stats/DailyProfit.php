<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\Stats;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\SleeperInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Adapter;

final class DailyProfit extends Command
{
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
        $this->setName('repeater:stats:daily-profit');
        $this->setDescription('Continously manages the daily profit stats table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $date = $this->getDayForUpdate();
        while ($date !== null && $this->shutdown->isShutdownModeEnabled() === false) {
            $isToday = date('Y-m-d 00:00:00', time()) === $date;
            $profits = $this->getProfitsForDay($date);
            foreach ($profits as $symbol => $profit) {
                if (Compare::getResult($profit['amount'], '0') === Compare::LEFT_GREATER_THAN) {
                    $this->logProfit($date, $symbol, $profit['amount'], $profit['amount_notional'], $isToday);
                }
            }
            if ($isToday) {
                $this->sleeper->sleep(60, function (): bool {
                    return $this->shutdown->isShutdownModeEnabled();
                });
            }
            $date = $this->getDayForUpdate();
        }
        if ($this->shutdown->isShutdownModeEnabled()) {
            $output->writeln(sprintf(
                "<fg=red>%s\tShutdown signal detected - %s",
                $this->now(),
                self::class
            ));
        }
        return $exitCode;
    }

    private function logProfit(string $date, string $symbol, string $amount, string $amountNotional, bool $isToday): void
    {
        if ($isToday === false || $this->hasData($date, $symbol) === false) {
            $this->insert($date, $symbol, $amount, $amountNotional);
        } else {
            $this->update($date, $symbol, $amount, $amountNotional);
        }
    }

    private function hasData(string $date, string $symbol): bool
    {
        $stmt = $this->adapter->query(
            'SELECT `id` FROM `repeater_stats_daily_profit` WHERE `date` = :date AND `symbol` = :symbol'
        );
        $result = $stmt->execute(['date' => $date, 'symbol' => $symbol]);
        return $result->count() === 1;
    }

    private function update(string $date, string $symbol, string $amount, string $amountNotional): void
    {
        $stmt = $this->adapter->query(
            'UPDATE `repeater_stats_daily_profit` SET `amount` = :amount, `amount_notional` = :amountNotional WHERE `date` = :date AND `symbol` = :symbol'
        );
        $stmt->execute([
            'date' => $date,
            'symbol' => $symbol,
            'amount' => $amount,
            'amountNotional' => $amountNotional
        ]);
    }

    private function insert(string $date, string $symbol, string $amount, string $amountNotional): void
    {
        $stmt = $this->adapter->query(
            'INSERT INTO `repeater_stats_daily_profit` VALUES (NULL, :symbol, :amount, :amountNotional, :date)'
        );
        $stmt->execute([
            'date' => $date,
            'symbol' => $symbol,
            'amount' => $amount,
            'amountNotional' => $amountNotional,
        ]);
    }

    private function getProfitsForDay(string $day): array
    {
        $profits = [];
        foreach ($this->fetchRepeaterArchivesForDay($day) as $archive) {
            $pair = Pair::getInstance($archive['symbol']);
            $quote = $pair->getQuote()->getSymbol();
            $base = $pair->getBase()->getSymbol();
            $fees = $this->getFeeTotal(
                $archive['symbol'],
                (int) $archive['buy_order_id'],
                (int) $archive['sell_order_id']
            );
            if (strtolower($fees['fee_currency']) === $quote) {
                $costBasis = Add::getResult(
                    Multiply::getResult($archive['buy_amount'], $archive['buy_price']),
                    $fees['fee_amount']
                );
                $profitQuote = Subtract::getResult(
                    Multiply::getResult($archive['sell_amount'], $archive['sell_price']),
                    $costBasis
                );
                $profitBase = Subtract::getResult($archive['buy_amount'], $archive['sell_amount']);

                // TODO: if we ever want to trade crypto/crypto pairs this needs some work...
                if ($quote === 'usd') {
                    $profitNotional = Multiply::getResult($profitBase, $archive['sell_price']);
                    foreach ([$base, $quote] as $key) {
                        if (($profits[$key] ?? null) === null) {
                            $profits[$key] = [
                                'amount' => '0',
                                'amount_notional' => '0'
                            ];
                        }
                    }
                    $profits[$base]['amount'] = Add::getResult($profits[$base]['amount'], $profitBase);
                    $profits[$base]['amount_notional'] = Add::getResult($profits[$base]['amount_notional'], $profitNotional);

                    $profits[$quote]['amount'] = Add::getResult($profits[$quote]['amount'], $profitQuote);
                    $profits[$quote]['amount_notional'] = Add::getResult($profits[$quote]['amount_notional'], $profitQuote);

                } else {
                    throw new \Exception(sprintf('Class "%s" is not yet written to handle non-USD quote pairs.', self::class));
                }

            } elseif (strtolower($fees['fee_currency']) === $pair->getBase()->getSymbol()) {
                throw new \Exception(sprintf('Class "%s" not written to handle fees in base currency.', self::class));
            } else {
                throw new \Exception(sprintf('Class "%s" not written to handle fees in base currency.', self::class));
            }
        }
        ksort($profits);
        return $profits;
    }

    private function getFeeTotal(string $symbol, int $buyOrderId, int $sellOrderId): array
    {
        $totalFees = '0';
        $feeCurrency = null;
        foreach ($this->getTransactionsForBuySellPair($buyOrderId, $sellOrderId, $symbol) as $transaction) {
            if ($feeCurrency === null) {
                $feeCurrency = $transaction['fee_currency'];
            } elseif ($feeCurrency !== $transaction['fee_currency']) {
                throw new \Exception(sprintf(
                    'Class "%s" only written to handle the same fee currency for all transactions of a given pair.',
                    self::class
                ));
            }
            $totalFees = Add::getResult($totalFees, $transaction['fee_amount']);
        }
        return [
            'fee_currency' => $feeCurrency,
            'fee_amount' => $totalFees,
        ];
    }

    private function getTransactionsForBuySellPair(int $buyOrderId, int $sellOrderId, string $symbol): array
    {
        $pair = Pair::getInstance($symbol);
        $stmt = $this->adapter->query(sprintf(
            'SELECT * FROM %s WHERE order_id IN (:buyOrderId, :sellOrderId)',
            'trade_history_' . $pair->getSymbol()
        ));
        $rows = $stmt->execute(['buyOrderId' => $buyOrderId, 'sellOrderId' => $sellOrderId]);
        $data = [];
        foreach ($rows as $row) {
            $data[] = $row;
        }
        return $data;
    }

    private function fetchRepeaterArchivesForDay(string $day): \Generator
    {
        $endDate = date('Y-m-d 00:00:00', strtotime($day . ' +1 day'));
        $stmt = $this->adapter->query(
            'SELECT * FROM `trade_repeater_archive` WHERE `sell_fill_timestamp` >= :dateFrom AND `sell_fill_timestamp` < :dateTo'
        );
        $rows = $stmt->execute(['dateFrom' => $day, 'dateTo' => $endDate]);
        foreach ($rows as $row) {
            yield $row;
        }
    }

    private function getDayForUpdate(): ?string
    {
        $day = null;
        $compareWithArchive = true;
        $result = $this->adapter->query(
            'SELECT `date` FROM `repeater_stats_daily_profit` ORDER BY `date` DESC LIMIT 1'
        )->execute();
        if ($result->count() === 0) {
            $compareWithArchive = false;
            $result = $this->adapter->query(
                'SELECT `sell_fill_timestamp` AS `date` FROM `trade_repeater_archive` WHERE `sell_fill_timestamp` IS NOT NULL ORDER BY `sell_fill_timestamp` ASC LIMIT 1'
            )->execute();
        }
        if ($result->count() === 1) {
            $day = $result->current()['date'];
            if ($compareWithArchive) {
                $result = $this->adapter->query(
                    'SELECT `sell_fill_timestamp` AS `date` ' .
                    'FROM `trade_repeater_archive` ' .
                    'WHERE `sell_fill_timestamp` >= :date ' .
                    'ORDER BY `sell_fill_timestamp` ' .
                    'ASC LIMIT 1'
                )->execute(['date' => date('Y-m-d 00:00:00', strtotime($day . ' +1 day'))]);
                if ($result->count() === 1) {
                    $day = $result->current()['date'];
                }
                $day = date('Y-m-d 00:00:00', strtotime($day));
            } else {
                $day = date('Y-m-d 00:00:00', strtotime($day));
            }
        }
        return $day;
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
