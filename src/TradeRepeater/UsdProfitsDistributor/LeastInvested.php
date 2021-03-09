<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\UsdProfitsDistributor;

use Kobens\Gemini\Command\Traits\KillFile;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Multiply;
use Zend\Db\Adapter\Adapter;
use Kobens\Core\EmergencyShutdownInterface;

final class LeastInvested implements RecordSelectorInterface
{
    use KillFile;
    use Traits\Fetch;

    private const KILL_FILE = 'kill_usd_profits_dist';

    private Adapter $adapter;

    private TradeResource $tradeResource;

    private EmergencyShutdownInterface $emergencyShutdown;

    public function __construct(
        Adapter $adapter,
        TradeResource $tradeResource,
        EmergencyShutdownInterface $emergencyShutdown
    ) {
        $this->adapter = $adapter;
        $this->tradeResource = $tradeResource;
        $this->emergencyShutdown = $emergencyShutdown;
    }

    public function get(): \Generator
    {
        $symbol = $this->getSymbol();
        if ($symbol === null) {
            return;
        }

        $lastId = 0;
        while (
            ! ($lastId === false) &&
            ! $this->emergencyShutdown->isShutdownModeEnabled() &&
            ! $this->killFileExists(self::KILL_FILE)
        ) {
            $trade = $this->fetch($this->tradeResource, $this->adapter, $lastId, $symbol);
            if ($trade) {
                $lastId = $trade->getId();
                yield $trade;
            } else {
                $lastId = false;
            }
        }
    }

    private function getSymbol(): ?string
    {
        $least = null;
        $leastAmount = null;
        foreach ($this->getTotals() as $symbol => $total) {
            if (
                $least === null ||
                Compare::getResult($leastAmount, $total) === Compare::RIGHT_LESS_THAN
            ) {
                $least = $symbol;
                $leastAmount = $total;
            }
        }
        return $least;
    }

    // TODO: Move this into its own class that caches the results. This isn't the only place we're collecting this data. Would be nice to fetch via API available
    private function getTotals(): array
    {
        $totals = [];
        $lastId = 0;
        do {
            $results = $this->adapter->query(
                'SELECT `id`, symbol, buy_amount, buy_price FROM trade_repeater WHERE `id` > :id ORDER BY `id` LIMIT 1000'
            )->execute(['id' => $lastId]);
            foreach ($results as $result) {
                $lastId = $result['id'];
                if (($totals[$result['symbol']] ?? null) === null) {
                    $totals[$result['symbol']] = '0';
                }
                $costBasis = Multiply::getResult($result['buy_price'], $result['buy_amount']);
                $deposit = Multiply::getResult($costBasis, '0.0035'); // TODO: Use constant or object to provide this
                $total = Add::getResult($costBasis, $deposit);
                $totals[$result['symbol']] = Add::getResult($totals[$result['symbol']], $total);
            }
        } while ($results->count());
        return $totals;
    }
}
