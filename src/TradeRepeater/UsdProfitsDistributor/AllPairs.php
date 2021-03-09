<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\UsdProfitsDistributor;

use Kobens\Gemini\Command\Traits\KillFile;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Zend\Db\Adapter\Adapter;
use Kobens\Core\EmergencyShutdownInterface;

final class AllPairs implements RecordSelectorInterface
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
        $lastId = 0;
        while (
            ! ($lastId === false) &&
            ! $this->emergencyShutdown->isShutdownModeEnabled() &&
            ! $this->killFileExists(self::KILL_FILE)
        ) {
            $trade = $this->fetch($this->tradeResource, $this->adapter, $lastId);
            if ($trade) {
                $lastId = $trade->getId();
                if (Pair::getInstance($trade->getSymbol())->getQuote()->getSymbol() === 'usd') {
                    yield $trade;
                }
            } else {
                $lastId = false;
            }
        }
    }
}
