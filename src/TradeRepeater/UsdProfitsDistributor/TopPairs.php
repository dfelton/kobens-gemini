<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\UsdProfitsDistributor;

use Kobens\Gemini\Command\Traits\KillFile;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Zend\Db\Adapter\Adapter;
use Kobens\Core\EmergencyShutdownInterface;

final class TopPairs implements RecordSelectorInterface
{
    use KillFile;
    use Traits\Fetch;

    private const KILL_FILE = 'kill_usd_profits_dist';

    private Adapter $adapter;

    private TradeResource $tradeResource;

    private EmergencyShutdownInterface $emergencyShutdown;

    /**
     * The limit on weekly top pairs to add to. We avoid
     * putting the limit in the SQL query directly in order
     * to avoid having to also query by symbols ending in
     * 'usd', but instead confirm with PHP that the quote
     * is indeed usd.
     *
     * @var integer
     */
    private int $weekTopCoinsLimit = 3;

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
        foreach ($this->getTopWeeklyCoinSymbols() as $symbol) {
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
    }

    private function getTopWeeklyCoinSymbols(): array
    {
        $symbols = [];
        $results = $this->adapter->query(
            'SELECT `symbol` FROM `trade_repeater_archive` WHERE `sell_fill_timestamp` >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY `symbol` ORDER BY COUNT(`id`) DESC'
        )->execute();
        foreach ($results as $result) {
            if (Pair::getInstance($result['symbol'])->getQuote()->getSymbol() === 'usd') {
                $symbols[] = $result['symbol'];
                if (count($symbols) >= $this->weekTopCoinsLimit) {
                    break;
                }
            }
        }
        return $symbols;
    }
}
